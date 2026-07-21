<?php

if (!function_exists('format_price')) {
    /**
     * Format price in Pakistani Rupees (PKR)
     *
     * @param float $amount
     * @param bool $showSymbol
     * @return string
     */
    function format_price($amount, $showSymbol = true)
    {
        $formatted = number_format($amount, 2);
        return $showSymbol ? 'Rs ' . $formatted : $formatted;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format currency with PKR symbol
     *
     * @param float $amount
     * @return string
     */
    function format_currency($amount)
    {
        return 'Rs ' . number_format($amount, 2);
    }
}
