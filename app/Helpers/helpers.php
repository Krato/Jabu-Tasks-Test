<?php


if (!function_exists('fixName')) {
    function fixName($name)
    {
        return str_replace('_', ' ', $name);
    }
}
