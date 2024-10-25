<?php

namespace Module\System\Traits;

use Module\System\Enums\EventStatus;
use Illuminate\Database\Eloquent\Model;
use Module\System\Models\SystemApproval;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait MustBeApproved
{
    /**
     * bypassApproval variable
     *
     * @var boolean
     */
    protected bool $bypassApproval = false;

    /**
     * bootMustBeApproved function
     *
     * @return void
     */
    public static function bootMustBeApproved(): void
    {
        static::creating(function (Model $model) {
            static::insertApprovalRequest($model);
        });

        static::updating(function (Model $model) {
            static::insertApprovalRequest($model);
        });
    }

    /**
     * insertApprovalRequest function
     *
     * @param Model $model
     * @return boolean|null
     */
    protected static function insertApprovalRequest(Model $model): ?bool
    {
        $filteredDirty = $model->getDirtyAttributes();
        $foreignKey = $model->getApprovalForeignKeyName();
        $foreignKeyValue = $filteredDirty[$foreignKey] ?? null;

        unset($filteredDirty[$foreignKey]);

        foreach ($filteredDirty as $key => $value) {
            if (isset($model->casts[$key]) && $model->casts[$key] === 'json') {
                $filteredDirty[$key] = json_decode(json: $value, associative: true);
            }
        }

        if ($model->isApprovalBypassed() || empty($filteredDirty)) {
            return null;
        }

        $approvalAttributes = $model->getApprovalAttributes();

        if (! empty($approvalAttributes)) {
            $noApprovalNeeded = collect($model->getDirty())
                ->except($approvalAttributes)
                ->toArray();

            if (! empty($noApprovalNeeded)) {
                $model->discardChanges();
                $model->forceFill($noApprovalNeeded);
            }
        }

        if (self::approvalModelExists($model) && empty($noApprovalNeeded)) {
            return false;
        }

        $model->approvals()->create([
            'source_data'   => $filteredDirty,
            'origin_data'   => $model->getOriginalMatchingChanges(),
            'officer_id'    => $foreignKeyValue,
        ]);

        if (empty($noApprovalNeeded)) {
            return false;
        }

        return true;
    }

    /**
     * getDirtyAttributes function
     *
     * @return array
     */
    protected function getDirtyAttributes(): array
    {
        if (empty($this->getApprovalAttributes())) {
            return $this->getDirty();
        }

        return collect($this->getDirty())
            ->only($this->getApprovalAttributes())
            ->toArray();
    }

    /**
     * getApprovalAttributes function
     *
     * @return array
     */
    public function getApprovalAttributes(): array
    {
        return $this->approvalAttributes ?? [];
    }

    /**
     * getApprovalForeignKeyName function
     *
     * @return string
     */
    public function getApprovalForeignKeyName(): string
    {
        return 'audited_by';
    }

    /**
     * isApprovalBypassed function
     *
     * @return boolean
     */
    public function isApprovalBypassed(): bool
    {
        return $this->bypassApproval;
    }

    /**
     * approvalModelExists function
     *
     * @param Model $model
     * @return boolean
     */
    protected static function approvalModelExists(Model $model): bool
    {
        return SystemApproval::where([
            ['state',       '=', EventStatus::Pending],
            ['source_data', '=', json_encode($model->getDirtyAttributes())],
            ['origin_data', '=', json_encode($model->getOriginalMatchingChanges())],
        ])->exists();
    }

    /**
     * getOriginalMatchingChanges function
     *
     * @return array
     */
    protected function getOriginalMatchingChanges(): array
    {
        return collect($this->getOriginal())
            ->only(collect($this->getDirtyAttributes())->keys())
            ->toArray();
    }

    /**
     * approvals function
     *
     * @return MorphMany
     */
    public function approvals(): MorphMany
    {
        return $this->morphMany(related: SystemApproval::class, name: 'approvalable');
    }

    /**
     * withoutApproval function
     *
     * @return static
     */
    public function withoutApproval(): static
    {
        $this->bypassApproval = true;

        return $this;
    }

    /**
     * callCastAttribute function
     *
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    public function callCastAttribute(mixed $key, mixed $value): mixed
    {
        if (array_key_exists($key, $this->casts)) {
            // If the value is already an array, return it as is
            if (is_array($value)) {
                return $value;
            }

            // Otherwise, cast the attribute to its defined type
            return $this->castAttribute($key, $value);
        }

        return $value;
    }
}
