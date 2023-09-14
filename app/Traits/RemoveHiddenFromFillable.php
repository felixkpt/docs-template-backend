<?php

namespace App\Traits;

trait RemoveHiddenFromFillable
{
    /**
     * Get the fillable attributes, excluding hidden attributes.
     *
     * @return array
     */
    public function getFillable($from_search_repo = false)
    {
        $fillable = parent::getFillable(); // Get the original fillable attributes.

        if (!$from_search_repo) return $fillable;

        // Remove hidden attributes from the fillable array.
        $fillable = array_diff($fillable, $this->getHidden());

        return $fillable;
    }
}
