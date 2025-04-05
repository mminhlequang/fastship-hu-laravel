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
    public static function getNameByLocale($model, $prefix = 'name')
    {
        $locale = app()->getLocale();  // Get current locale (vi, en, fr, ...)
        $column = $prefix . '_' . $locale; // Create the column name based on the locale
        return $model->$column ?? 'No text';  // Return the localized name or null if not found
    }

    public static function greetBasedOnTime()
    {
        $hour = date('H'); // Get the current hour in 24-hour format

        if ($hour >= 5 && $hour < 12) {
            return "Good morning!";
        } elseif ($hour >= 12 && $hour < 18) {
            return "Good afternoon!";
        } else {
            return "Good evening!";
        }
    }



}
