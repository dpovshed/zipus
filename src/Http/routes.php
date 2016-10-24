<?php

if (config('app.debug')) {
    Route::get('/zipus-test', function() {
        $zipcode = '10282';
        $city = app()->make('zipcode')->getCity($zipcode);
        echo "Lookup for \"ucword'ed\" City name for zipcode $zipcode: ";
        var_dump($city);
        echo PHP_EOL;

        $data = app()->make('zipcode')->getData($zipcode);
        echo "Lookup for all data for zipcode $zipcode: ";
        var_dump($data);
        echo PHP_EOL;
    });
}
