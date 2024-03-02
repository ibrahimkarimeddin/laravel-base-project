<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\City;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class AddressService
{
  const APY_KEY ='';
  
    // calc dist between two lat,long 

    public static  function haversineDistance($lat1, $lon1, $lat2, $lon2, $unit)
    {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);
  
      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
        return ($miles * 0.8684);
      } else {
        return $miles;
      }
    }

   
  public static function getPlaceNameFromLatLong($lat, $long)
  {
    $KEY = self::APY_KEY;

    $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=true&language=ar&key=$KEY");

    $response =  $response->json();

    $locality = '';
    $sublocality = '';
    $sublocality_level_1 = '';
    $route = '';
    $neighborhood = '';

    foreach ($response['results'] as $address_components) {


      foreach ($address_components['address_components'] as $address_component) {

        foreach ($address_component['types'] as $type) {

          if ($type == 'sublocality') {
            $sublocality = $address_component['short_name'];
          }
          if ($type == 'locality') {
            $locality = $address_component['short_name'];
          }
          if ($type == 'neighborhood') {
            $neighborhood = $address_component['short_name'];
          }
          if ($type == 'sublocality_level_1') {

            $sublocality_level_1 = $address_component['short_name'];
          }
          if ($type == 'route') {

            if ($address_component['short_name'] != 'Unnamed Road' && $address_component['short_name'] != 'طريق بدون اسم') {
              $route = $address_component['short_name'];
            }
          }
        }
      }
    }

    $address = $locality ?? 'UN KNOW';

    if ($neighborhood != '') {

      $address =  $address . ',' . $neighborhood;
    }
    if ($sublocality_level_1 != '') {

      if ($sublocality_level_1 == $sublocality) {
        $address =  $address . ',' . $sublocality;
      } else {
        $address =  $address . ',' . $sublocality_level_1;
      }
    }

    if ($route != '') {

      $address =  $address . ',' . $route;
    }

    return $address;
  }

  
  public static function getDistanceWithTime($lat1, $long1, $lat2, $long2) 
  {
      $KEY = self::APY_KEY;
      $response = Http::get("https://maps.googleapis.com/maps/api/directions/json?origin=$lat1,$long1&destination=$lat2,$long2&key=$KEY&mode=driving");

        $data = $response->json();

      $distance =  $data['routes'][0]['legs'][0]['distance']['text'];
      $time =  $data['routes'][0]['legs'][0]['duration']['text'];


    return [
      strstr($distance, ' ', true),
      strstr($time, ' ', true),
    ];

  }

}
