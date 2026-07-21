<?php

use App\Services\AssetManager\AssetManager;

if (!function_exists('asset_manager')) {
    /**
     * Get the asset manager instance.
     *
     * @return \App\Services\AssetManager\AssetManager
     */
    function asset_manager()
    {
        return app('asset.manager');
    }
}

if (!function_exists('register_style')) {
    /**
     * Register a stylesheet.
     *
     * @param string $name
     * @param string $path
     * @param array $dependencies
     * @param string|null $media
     * @return \App\Services\AssetManager\AssetManager
     */
    function register_style($name, $path, $dependencies = [], $media = 'all')
    {
        return asset_manager()->registerStyle($name, $path, $dependencies, $media);
    }
}

if (!function_exists('register_script')) {
    /**
     * Register a script.
     *
     * @param string $name
     * @param string $path
     * @param array $dependencies
     * @param bool $inFooter
     * @param bool $defer
     * @param bool $async
     * @return \App\Services\AssetManager\AssetManager
     */
    function register_script($name, $path, $dependencies = [], $inFooter = true, $defer = false, $async = false)
    {
        return asset_manager()->registerScript($name, $path, $dependencies, $inFooter, $defer, $async);
    }
}

if (!function_exists('enqueue_style')) {
    /**
     * Enqueue a stylesheet for the current request.
     *
     * @param string $name
     * @return \App\Services\AssetManager\AssetManager
     */
    function enqueue_style($name)
    {
        return asset_manager()->enqueueStyle($name);
    }
}

if (!function_exists('enqueue_script')) {
    /**
     * Enqueue a script for the current request.
     *
     * @param string $name
     * @return \App\Services\AssetManager\AssetManager
     */
    function enqueue_script($name)
    {
        return asset_manager()->enqueueScript($name);
    }
}

if (!function_exists('render_styles')) {
    /**
     * Render enqueued stylesheets.
     *
     * @return string
     */
    function render_styles()
    {
        return asset_manager()->renderStyles();
    }
}

if (!function_exists('render_scripts')) {
    /**
     * Render enqueued scripts.
     *
     * @param bool $inFooter
     * @return string
     */
    function render_scripts($inFooter = false)
    {
        return asset_manager()->renderScripts($inFooter);
    }
}