<?php

namespace App\Services;

use App\Models\AppSetting;

class  LatLongService {

    public $lat ;
    public $long ;
    public $distance; 
    public  $r_earth = 6378;
    public $pi =M_PI;
    public $hash ; 
    public $number_layer  ; 
    function __construct($lat , $long,$distance)
    {
        $this->lat = $lat;
        $this->long = $long;
        $this->distance =$distance;
        $this->hash = new GeoHashService();
        // $this->number_layer = AppSetting::where('key',"world_layer")->first()->value;   
        $this->number_layer = 12;
    }



    public function ToNorthPosition()
    {
       
        $new_latitude = $this->lat + ($this->distance / $this->r_earth) * (180 / $this->pi);
        return $this->hash->encode($new_latitude , $this->long, $this->number_layer);
        
    }
    
    public function ToEastPosition()
    {
       
         $new_longitude = $this->long + ($this->distance / $this->r_earth) * (180 / $this->pi) / cos($this->lat * $this->pi / 180);
        return $this->hash->encode($this->lat , $new_longitude, $this->number_layer);
        
    }
    
    public function ToSouthPosition()
    {
       
         $new_latitude = $this->lat - ($this->distance / $this->r_earth) * (180 / $this->pi);
         return $this->hash->encode($new_latitude , $this->long, $this->number_layer);

        
    }
    
    public function ToWestPosition()
    {
        $new_longitude = $this->long - ($this->distance / $this->r_earth) * (180 / $this->pi) / cos($this->lat * $this->pi / 180);
        return $this->hash->encode($this->lat , $new_longitude, $this->number_layer);

    }

    public function ToSouthWestPosition()
    {
        $new_longitude = $this->long - ($this->distance / $this->r_earth) * (180 / $this->pi) / cos($this->lat * $this->pi / 180);
        $new_latitude = $this->lat - ($this->distance / $this->r_earth) * (180 / $this->pi);
        return $this->hash->encode($new_latitude , $new_longitude, $this->number_layer);

    }
    public function ToNorthWestPosition()
    {
        $new_longitude = $this->long - ($this->distance / $this->r_earth) * (180 / $this->pi) / cos($this->lat * $this->pi / 180);
        $new_latitude = $this->lat + ($this->distance / $this->r_earth) * (180 / $this->pi);
        return $this->hash->encode($new_latitude , $new_longitude, $this->number_layer);

    }
    public function ToNorthEastPosition()
    {
        $new_latitude = $this->lat + ($this->distance / $this->r_earth) * (180 / $this->pi);
         $new_longitude = $this->long + ($this->distance / $this->r_earth) * (180 / $this->pi) / cos($this->lat * $this->pi / 180);
        return $this->hash->encode($new_latitude , $new_longitude, $this->number_layer);
        
    }
    public function ToSouthEastPosition()
    {
        $new_latitude = $this->lat - ($this->distance / $this->r_earth) * (180 / $this->pi);
         $new_longitude = $this->long + ($this->distance / $this->r_earth) * (180 / $this->pi) / cos($this->lat * $this->pi / 180);
        return $this->hash->encode($new_latitude , $new_longitude, $this->number_layer);
        
    }

}