<?php

namespace Module\System\Traits;

use ReflectionMethod;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Module\System\Attributes\SearchUsingPrefix;
use Module\System\Attributes\SearchUsingFullText;

trait Searchable
{
    /**
     * toSearchableArray function
     *
     * @return array
     */
    protected function toSearchableArray(): array
    {
        return [
            // 'id' => 'id',
            'name' => 'name',
        ];
    }

    /**
     * resolveRouteBinding function.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($this->hasSoftDeleted()) {
            return $this
                ->withTrashed()
                ->where($field ?? $this->getRouteKeyName(), $value)
                ->first();
        }

        return $this
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->first();
        
    }

    /**
     * scopeApplyMode function
     *
     * @param Builder $query
     * @param [type] $mode
     * @return Builder
     */
    public function scopeApplyMode(Builder $query, $trashed): Builder
    {
        return $trashed ? $query->onlyTrashed() : $query;
    }

    /**
     * scopeForCombo function
     *
     * @param Builder $query
     * @return void
     */
    public function scopeForCombo(Builder $query)
    {
        return $query
            ->select('name AS title', 'id AS value')
            ->get();
    }

    /**
     * scopeSearch function
     *
     * @param Builder $builder
     * @param [type] $search
     * @return Builder
     */
    public function scopeSearch(Builder $builder, $search): Builder
    {
        $columns = $this->toSearchableArray();
        $prefixColumns = $this->getPrefixColumns($builder);
        $fullTextColumns = $this->getFullTextColumns($builder);

        if (!$search || count($columns) <= 0) {
            return $builder;
        }

        if (method_exists($this, 'scopeAlterSearchable')) {
            $builder = $this->scopeAlterSearchable($builder);
        }

        return $builder->where(function ($query) use ($search, $columns, $prefixColumns, $fullTextColumns) {
            $connectionType = $this->getConnection()->getDriverName();

            $canSearchPrimaryKey = ctype_digit($search) &&
                in_array($this->getKeyType(), ['int', 'integer']) &&
                ($connectionType != 'pgsql' || $search <= PHP_INT_MAX) &&
                array_key_exists($this->getKeyName(), $columns);

            if ($canSearchPrimaryKey) {
                $query->orWhere($this->getQualifiedKeyName(), $search);
            }

            foreach ($columns as $key => $column) {
                if (in_array($column, $fullTextColumns)) {
                    $query->orWhereFullText(
                        $this->qualifyColumn($column),
                        $search
                    );
                } else {
                    if ($canSearchPrimaryKey && $column === $this->getKeyName()) {
                        continue;
                    }

                    $query->orWhere(
                        $this->qualifyColumn($column),
                        'ilike',
                        in_array($column, $prefixColumns) ? $search . '%' : '%' . $search . '%',
                    );
                }
            }
        });
    }

    /**
     * scopeSortBy function
     *
     * @param Builder $query
     * @param [type] $sortBy
     * @return Builder
     */
    public function scopeSortBy(Builder $query, $sortBy = null): Builder
    {
        if ($sortBy) {
            if (str($sortBy)->contains(":")) {
                list($fieldName, $ordered) = explode(":", $sortBy);

                $query = $query->orderBy($fieldName, $ordered);
            }
        } else {
            if (is_array($this->defaultOrder)) {
                foreach ($this->defaultOrder as $order) {
                    if (str($order)->contains(":")) {
                        list($fieldName, $ordered) = explode(":", $order);

                        $query = $query->orderBy($fieldName, $ordered);
                    } else {
                        $query = $query->orderBy($order);
                    }
                }
            } else {
                if (str($this->defaultOrder)->contains(":")) {
                    list($fieldName, $ordered) = explode(":", $this->defaultOrder);

                    $query = $query->orderBy($fieldName, $ordered);
                } else {
                    $query = $query->orderBy($this->defaultOrder);
                }
            }
        }

        if (method_exists($this, 'scopeAlterOrderBy')) {
            $this->scopeAlterOrderBy($query);
        }

        return $query;
    }

    /**
     * getPrefixColumns function
     *
     * @param Builder $builder
     * @return array
     */
    protected function getPrefixColumns(Builder $builder): array
    {
        return $this->getSearchAttributeColumns($builder, SearchUsingPrefix::class);
    }

    /**
     * getFullTextColumns function
     *
     * @param Builder $builder
     * @return array
     */
    protected function getFullTextColumns(Builder $builder): array
    {
        return $this->getSearchAttributeColumns($builder, SearchUsingFullText::class);
    }

    /**
     * getSearchAttributeColumns function
     *
     * @param Builder $builder
     * @param [type] $attributeClass
     * @return array
     */
    protected function getSearchAttributeColumns(Builder $builder, $attributeClass): array
    {
        $columns = [];

        if (PHP_MAJOR_VERSION < 8) {
            return [];
        }

        foreach ((new ReflectionMethod($this, 'toSearchableArray'))->getAttributes() as $attribute) {
            if ($attribute->getName() !== $attributeClass) {
                continue;
            }

            $columns = array_merge($columns, Arr::wrap($attribute->getArguments()[0]));
        }

        return $columns;
    }
}