<?php

namespace Apni\SeoSdk;

class SeoSdk
{
    protected static array $data = [];
    protected static array $routes = [];
    protected static array $cities = ['adilabad', 'agar malwa', 'agra', 'ahmadabad', 'ahmednagar', 'aizawl', 'ajmer', 'akola', 'alappuzha', 'aligarh', 'alipurduar', 'alirajpur', 'alluri sitharama raju', 'almora', 'alwar', 'ambala', 'ambedkar nagar', 'amethi', 'amravati', 'amreli', 'amritsar', 'amroha', 'anakapalli', 'anand', 'anantapur', 'anantnag', 'anjaw',];

    protected static array $requiredFields = [
        'title',
        'description',
        'keywords',
        'author',
        'image_url',
        'region',
        'country',
        'state',
        'city',
        'pincode',
        'copyright',
        'address_line_1'
    ];

    protected static array $optionalFields = [
        'address_line_2' => '',
        'twitter_site' => '',
    ];

    protected static string $clity_name = "";

    // One-time setup for routes
    public static function init(array $routes)
    {
        self::$routes = $routes;
    }

    public static function set(array $data)
    {
        foreach (self::$requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new \InvalidArgumentException("SEO: Missing required field: {$field}");
            }
        }

        // Merge optional fields if not provided
        foreach (self::$optionalFields as $field => $default) {
            if (!isset($data[$field])) {
                $data[$field] = $default;
            }
        }

        // Save the validated and merged data
        self::$data = $data;
    }

    public static function renderHead(): string
    {
        $request_city = self::getCity();
        self::$clity_name = $request_city ? ucfirst($request_city) : '';

        $content = self::$data;

        $title = self::join_city_name($content['title']);
        $description = self::join_city_name($content['description']);
        $keywords = self::join_city_name($content['keywords']);

        $author = $content['author'];
        $image_url = $content['image_url'];
        $region = $content['region'];
        $country = $content['country'];
        $state = $content['state'];
        $city = $content['city'];
        $pincode = $content['pincode'];
        $copyright = $content['copyright'];
        $address_line_1 = $content['address_line_1'];

        $address_line_2 = $content['address_line_2'] ?? '';
        $twitter_site = $content['twitter_site'] ?? '';

        $url = $_SERVER['REQUEST_URI'] ?? '';
        $host = $_SERVER['HTTP_HOST'] ?? '';


        $meta = "
            <title>{$title}</title>
            <meta name='description' content='{$description}'>
            <meta name='keywords' content='{$keywords}'>
            <meta name='author' content='{$author}'>
            <meta name='twitter:site' content='@{$twitter_site}'>
            <meta name='twitter:title' content='{$title}'>
            <meta name='twitter:description' content='{$description}'>
            <meta name='twitter:image' content='{$image_url}'>
            <meta property='og:title' content='{$title}'>
            <meta property='og:site_name' content='{$author}'>
            <meta property='og:url' content='{$url}'>
            <meta property='og:description' content='{$description}'>
            <meta property='og:image' content='{$image_url}'>
            <meta name='abstract' content='{$title}'>
            <meta name='Classification' content='{$description}'>
            <meta name='dc.source' content='{$host}'>
            <meta name='dc.title' content='{$title}'>
            <meta name='dc.keywords' content='{$keywords}'>
            <meta name='dc.subject' content='{$author}'>
            <meta name='dc.description' content='{$description}'>
            <meta name='geo.region' content='{$region}'>
            <meta name='State' content='{$state}'>
            <meta name='City' content='{$city}'>
            <meta name='address' content='{$address_line_1},{$address_line_2},{$city},{$state},{$country},{$pincode}.'>
            <meta name='copyright' content='© {$copyright}'>
            <meta name='subject' content='{$title}'>
            <meta name='generator' content='{$host}'>
            <meta name='author' content='{$author}'>
            <link rel='canonical' href='{$url}' />
            
            <meta name='rating' content='general'>
            <meta name='ROBOTS' content='index, follow'/>
            <meta name='revisit-after' content='Daily'>
            <meta name='googlebot' content='index, follow'>
            <meta name='bingbot' content='index, follow'>
            <meta name='twitter:card' content='summary'>
            <meta property='og:type' content='website'>
            <meta name='distribution' content='global'>
            <meta name='language' content='english'>
            <meta name='document-type' content='Public'>
            <meta name='document-rating' content='Safe for Kids'>
            <meta name='Expires' content='never'>
            <meta name='HandheldFriendly' content='True'>
            <meta name='YahooSeeker' content='Index,Follow'>
            <meta name='robots' content='ALL'>
        ";

        return $meta;
    }

    protected static function getCity(): ?string
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($url, '/'));

        if (empty($segments)) return null;

        $lastSegment = strtolower(end($segments)); // get only the last segment

        // Check direct match
        if (in_array($lastSegment, self::$cities)) {
            return $lastSegment;
        }

        // Check if last segment contains city in slug: e.g., item-city or city-item
        foreach (self::$cities as $city) {
            if (str_contains($lastSegment, $city)) {
                return $city;
            }
        }

        return null;
    }

    protected static function join_city_name(string $text): string
    {
        $city = self::$clity_name;

        if (!empty($city)) {
            $text_array = explode(",", $text);
            $new_text = "";

            foreach ($text_array as $value) {
                $new_text .= trim($value) . " in " . $city;
            }

            return $new_text;
        }
        return $text;
    }
}
