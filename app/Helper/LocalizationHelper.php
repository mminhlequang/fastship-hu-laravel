<?php

namespace App\Helper;


class LocalizationHelper
{
    /**
     * Get the name of the product based on the current locale.
     *
     * @param $model
     * @return string|null
     */
    public static function getNameByLocale($model)
    {
        $locale = app()->getLocale();  // Get current locale (vi, en, fr, ...)
        $column = 'name_' . $locale; // Create the column name based on the locale
        return $model->$column ?? 'No Name';  // Return the localized name or null if not found
    }
}
