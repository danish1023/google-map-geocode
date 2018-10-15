<?php

use mddanish\TechStreet\GeoCode;
require_once "vendor/autoload.php";

$obj = new GeoCode();
echo $obj->latlonFromAddress('New Delhi','YOUR_API_KEY');
