<?php

namespace Catzilla\ScoutNoindex;

trait Searchable
{
    use \Laravel\Scout\Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        if (property_exists($this, 'index')) {
            foreach ($array as $key => $value) {
                if (!in_array($key, $this->index)) {
                    unset($array[$key]);
                }
            }
        }

        if (property_exists($this, 'noindex')) {
            foreach ($this->noindex as $key) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $dirty = $this->getDirty();

        if (property_exists($this, 'index')) {
            foreach ($dirty as $key => $value) {
                if (in_array($key, $this->index)) {
                    return parent::save($options);
                }
            }
        }

        if (property_exists($this, 'noindex')) {
            foreach ($dirty as $key => $value) {
                if (!in_array($key, $this->noindex)) {
                    return parent::save($options);
                }
            }
        }

        static::disableSearchSyncing();
        $result = parent::save($options);
        static::enableSearchSyncing();

        return $result;
    }
}
