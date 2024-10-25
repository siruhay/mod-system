<?php

namespace Module\System\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait HasMeta
{
    /**
     * The metas variable
     *
     * @var array
     */
    protected $metas = [];

    /**
     * Undocumented function
     *
     * @param [type] $key
     * @return void
     */
    private function getMeta($key)
    {
        $metaFld = parent::getAttribute('meta');

        if (is_null($metaFld) || empty($metaFld) || !array_key_exists($key, $metaFld)) {
            return null;
        }

        return $metaFld[$key];
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $columns = Cache::flexible($this->getTable(), [60, 3600], function () {
            return Schema::connection($this->connection)->getColumnListing($this->getTable());
        });

        if (in_array($key, $columns) || array_key_exists($key, parent::getAttributes())) {
            parent::setAttribute($key, $value);
        } else {
            $this->setMeta($key, $value);
        }
    }

    /**
     * The setMeta function
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    private function setMeta($key, $value)
    {
        if (empty($this->metas)) {
            $this->metas = parent::getAttribute('meta');
        }

        $this->metas[$key] = $value;
    }

    /**
     * The getAttribute function
     *
     * @param [type] $key
     * @return void
     */
    public function getAttribute($key)
    {
        if (($attr = parent::getAttribute($key)) !== null) {
            return $attr;
        }

        return $this->getMeta($key);
    }

    /**
     * The save function
     *
     * @param array $options
     * @return void
     */
    public function save(array $options = [])
    {
        if (!empty($this->metas)) {
            parent::setAttribute('meta', $this->metas);
        }

        return parent::save($options);
    }
}
