<?php

use Apni\SeoSdk\SeoSdk;

if (!function_exists('seo_head')) {
    function seo_head(array $data = []): string
    {
        SeoSdk::set($data);  // static call
        return SeoSdk::renderHead(); // static call
    }
}
