<?php

use Apni\SeoSdk\SeoSdk;

if (!function_exists('seo_head')) {
    function seo_head(array $data = []): string
    {
        $seo = new SeoSdk();
        return $seo->set($data)->renderHead();
    }
}
