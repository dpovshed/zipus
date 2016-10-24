# United States Zipcode Lookup database 

This is a Laravel 5 package for easy and simple lookup for geographic data by U.S. zipcode. While there are a few nice solutions like http://zippopotam.us for online lookup, sometimes i might be preferable to have all the data locally.
  
The package using the data from http://federalgovernmentzipcodes.us/ . There is an Artisan command implemented to perform automatic update of the data.  

## Installation

### Step 1

Add this to your `composer.json`
    
    {
        "require": {            
            "dpovshed/zipus": "1.*"
        }
    }

then install package as usual.

### Step 2

Run the following command:

php artisan zipus-import

If everything is fine, as a result in your cache directory you'll have JSONed arrays with the data.

If your application is in debug mode, i.e. *APP_DEBUG* is set to *true* in the *.env* file, you may visit an URL 
http://example.com/zipus-test to check the lookup process.

## Usage

Lookup functionality is provided as a service, so use construction like

    $city = app()->make('zipcode')->getCity('10282');

to get city name for a particular zipcode. Result is a string with a city name. 
To get the all data available please use function named *getData()*: 

    $city = app()->make('zipcode')->getData('10282');

You will get a resulting array like:
```php
[
  'ZipCodeType' => string 'STANDARD' (length=8)
  'City' => string 'NEW YORK' (length=8)
  'State' => string 'NY' (length=2)
  'LocationType' => string 'PRIMARY' (length=7)
  'Lat' => string '40.71' (length=5)
  ...
];
```

All the elements of an array would be named exactly as a column in original CSV file form http://federalgovernmentzipcodes.us . Please note that package used a database where a patricular zipcode is resolved only to one primary address.

In case passed string is not a valid U.S. zipcode, as a result you will get unchanged zipcode with *getCity()* and an empty array with *getData()*.
