<?php

// Include Asset Management helper functions
require_once __DIR__ . '/../app/Helpers/asset_helpers.php';

function is_current_route($routeName)
{
    return request()->routeIs($routeName) ? 'active' : '';
}

if (!function_exists('store_setting')) {
    function store_setting($key, $default = null)
    {
        return \App\Services\StoreSettingsService::get($key, $default);
    }
}


function in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}


function getCategoriesArray($parent, $child = null)
{
    $categories = array(
        'dashboard', 'tables', 'wallet', 'RTL',

        'account' => array(
            'profile',
            'users',
        ),
    );

    if ($child)
        return $categories[$parent][$child];
    else
        return $categories[$parent];
}
