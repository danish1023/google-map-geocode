<?php

namespace mddanish\TechStreet;

class GeoCode
{
    public function zipcodeFromAddress($address, $key = null)
    {
        $formattedAddr = str_replace(' ', '+', $address);

        $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&address=' . $formattedAddr . '&sensor=true_or_false');
        $output1 = json_decode($geocodeFromAddr);

        if ($output1->status == 'OK') {
            $latitude = $output1->results[0]->geometry->location->lat;
            $longitude = $output1->results[0]->geometry->location->lng;
        } else {
            return $output1->error_message;
        }

        $geocodeFromLatlon = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&latlng=' . $latitude . ',' . $longitude . '&sensor=true_or_false');
        $output2 = json_decode($geocodeFromLatlon);

        if ($output2->status == 'OK') {
            $addressComponents = $output2->results[0]->address_components;
            foreach ($addressComponents as $addrComp) {
                if ($addrComp->types[0] == 'postal_code') {
                    $zipcode = $addrComp->long_name;
                } else {
                    $zipcode = 'Not found!';
                }
            }
            return $zipcode;
        } else {
            return $output2->error_message;
        }
    }
    public function latFromAddress($address, $key = null)
    {
        $geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&key=' . $key . '&address=' . urlencode($address) . '&sensor=true_or_false');
        $output = json_decode($geocode);

        if ($output->status == 'OK') {
            return $output->results[0]->geometry->location->lat;
        } else {
            return $output->error_message;
        }
    }
    public function lonFromAddress($address, $key = null)
    {
        $geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&address=' . urlencode($address) . '&sensor=true_or_false');
        $output = json_decode($geocode);

        if ($output->status == 'OK') {
            return $output->results[0]->geometry->location->lng;
        } else {
            return $output->error_message;
        }
    }
    public function latlonFromAddress($address, $key = null)
    {
        $geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&address=' . urlencode($address) . '&sensor=true_or_false');
        $output = json_decode($geocode);

        if ($output->status == 'OK') {
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            return $latitude . ',' . $longitude;
        } else {
            return $output->error_message;
        }
    }
    public function distanceFromAddress($addressFrom, $addressTo, $unit = null, $key = null)
    {
        $formattedAddrFrom = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo = str_replace(' ', '+', $addressTo);

        $geocodeFrom = file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyALAJJv_UUyOlg1yCoErHphotdqY3P8Dm4&address=' . $formattedAddrFrom . '&sensor=false');
        $outputFrom = json_decode($geocodeFrom);

        if ($outputFrom->status == 'OK') {
            $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
            $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
        } else {
            return $outputFrom->error_message;
        }

        $geocodeTo = file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyALAJJv_UUyOlg1yCoErHphotdqY3P8Dm4&address=' . $formattedAddrTo . '&sensor=false');
        $outputTo = json_decode($geocodeTo);

        if ($outputTo->status == 'OK') {
            $latitudeTo = $outputTo->results[0]->geometry->location->lat;
            $longitudeTo = $outputTo->results[0]->geometry->location->lng;
        } else {
            return $outputTo->error_message;
        }

        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) + cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        $unit = strtoupper($unit);

        if ($unit == "KM") {
            return ($miles * 1.609344);
        } else {
            return $miles;
        }
    }
    public function addressFromLatlon($latitude, $longitude, $key = null)
    {
        $geocodeFromLatlon = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&latlng=' . $latitude . ',' . $longitude . '&sensor=true_or_false');
        $output = json_decode($geocodeFromLatlon);

        if ($output->status == 'OK') {
            return $output->results[0]->formatted_address;
        } else {
            return $output->error_message;
        }
    }
    public function pluscodeFromLatlon($latitude, $longitude, $type = null, $key = null)
    {
        $geocodeFromLatlon = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&latlng=' . $latitude . ',' . $longitude . '&sensor=true_or_false');
        $output = json_decode($geocodeFromLatlon);

        if ($output->status == 'OK') {
            if ($type == 'compound') {
                return $output->plus_code->compound_code;
            } else {
                return $output->plus_code->global_code;
            }

        } else {
            return $output->status;
        }
    }
}
