<?php

namespace App\Services\AssetManager;

class AssetManager
{
    /**
     * Registered stylesheets.
     *
     * @var array
     */
    protected $styles = [];

    /**
     * Registered scripts.
     *
     * @var array
     */
    protected $scripts = [];

    /**
     * Enqueued stylesheets for the current request.
     *
     * @var array
     */
    protected $enqueuedStyles = [];

    /**
     * Enqueued scripts for the current request.
     *
     * @var array
     */
    protected $enqueuedScripts = [];

    /**
     * Register a stylesheet.
     *
     * @param string $name
     * @param string $path
     * @param array $dependencies
     * @param string|null $media
     * @return $this
     */
    public function registerStyle($name, $path, $dependencies = [], $media = 'all')
    {
        $this->styles[$name] = [
            'path' => $path,
            'dependencies' => $dependencies,
            'media' => $media,
        ];

        return $this;
    }

    /**
     * Register a script.
     *
     * @param string $name
     * @param string $path
     * @param array $dependencies
     * @param bool $inFooter
     * @param bool $defer
     * @param bool $async
     * @return $this
     */
    public function registerScript($name, $path, $dependencies = [], $inFooter = true, $defer = false, $async = false)
    {
        $this->scripts[$name] = [
            'path' => $path,
            'dependencies' => $dependencies,
            'in_footer' => $inFooter,
            'defer' => $defer,
            'async' => $async,
        ];

        return $this;
    }

    /**
     * Enqueue a stylesheet for the current request.
     *
     * @param string $name
     * @return $this
     */
    public function enqueueStyle($name)
    {
        if (!isset($this->styles[$name])) {
            return $this;
        }

        $this->enqueuedStyles[$name] = $this->styles[$name];

        // Enqueue dependencies
        foreach ($this->styles[$name]['dependencies'] as $dependency) {
            $this->enqueueStyle($dependency);
        }

        return $this;
    }

    /**
     * Enqueue a script for the current request.
     *
     * @param string $name
     * @return $this
     */
    public function enqueueScript($name)
    {
        if (!isset($this->scripts[$name])) {
            return $this;
        }

        $this->enqueuedScripts[$name] = $this->scripts[$name];

        // Enqueue dependencies
        foreach ($this->scripts[$name]['dependencies'] as $dependency) {
            $this->enqueueScript($dependency);
        }

        return $this;
    }

    /**
     * Render enqueued stylesheets.
     *
     * @return string
     */
    public function renderStyles()
    {
        $output = '';

        foreach ($this->sortByDependencies($this->enqueuedStyles) as $name => $style) {
            $attributes = [
                'rel="stylesheet"',
                'href="' . asset($style['path']) . '"',
                'media="' . $style['media'] . '"',
                nonce_attr(),
            ];

            $output .= '<link ' . implode(' ', $attributes) . '>' . PHP_EOL;
        }

        return $output;
    }

    /**
     * Render enqueued scripts.
     *
     * @param bool $inFooter
     * @return string
     */
    public function renderScripts($inFooter = false)
    {
        $output = '';

        foreach ($this->sortByDependencies($this->enqueuedScripts) as $name => $script) {
            // Skip if this script doesn't belong in this location
            if ($script['in_footer'] !== $inFooter) {
                continue;
            }

            $attributes = [
                'src="' . asset($script['path']) . '"',
                nonce_attr(),
            ];

            if ($script['defer']) {
                $attributes[] = 'defer';
            }

            if ($script['async']) {
                $attributes[] = 'async';
            }

            $output .= '<script ' . implode(' ', $attributes) . '></script>' . PHP_EOL;
        }

        return $output;
    }

    /**
     * Sort assets by dependencies.
     *
     * @param array $assets
     * @return array
     */
    protected function sortByDependencies($assets)
    {
        $sorted = [];
        $visited = [];

        foreach ($assets as $name => $asset) {
            $this->visitAsset($name, $assets, $sorted, $visited);
        }

        return $sorted;
    }

    /**
     * Visit an asset and its dependencies.
     *
     * @param string $name
     * @param array $assets
     * @param array &$sorted
     * @param array &$visited
     * @return void
     */
    protected function visitAsset($name, $assets, &$sorted, &$visited)
    {
        if (!isset($assets[$name]) || isset($visited[$name])) {
            return;
        }

        $visited[$name] = true;

        foreach ($assets[$name]['dependencies'] as $dependency) {
            if (isset($assets[$dependency])) {
                $this->visitAsset($dependency, $assets, $sorted, $visited);
            }
        }

        $sorted[$name] = $assets[$name];
    }
}