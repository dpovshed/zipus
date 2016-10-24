<?php

namespace Dpovshed\Zipus;

use Log;

class ZipCityLookup
{

    /**
     * @var array $data
     *
     * All the details for zipcode.
     */
    static protected $data = false;

    /**
     * @var array $cities
     *
     * Mapping zipcode to city name.
     */
    static protected $cities = false;

    /**
     * @param string $zip
     *   Zip code to lookup.
     *
     * @return string
     *   City name if found, original zipcode otherwise.
     */
    public function getCity($zip)
    {
        if (empty(self::$cities)) {
            self::$cities = json_decode(file_get_contents(storage_path('framework/cache/zipus_city.json')), true);
        }
        if (isset(self::$cities[$zip])) {
            return self::$cities[$zip];
        }
        return $zip;
    }

    /**
     * @param string $zip
     *   Zip code to lookup.
     *
     * @return array
     *   If zipcode is known, return all the data. Otherwise return empty array.
     */
    public function getData($zip)
    {
        if (empty(self::$data)) {
            self::$data = json_decode(file_get_contents(storage_path('framework/cache/zipus_all.json')), true);
        }
        if (isset(self::$data[$zip])) {
            return self::$data[$zip];
        }
        return [];
    }
}
