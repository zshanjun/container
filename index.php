<?php

require 'Visit.php';
require 'Leg.php';
require 'Train.php';
require 'Car.php';
require 'Traveller.php';
require 'Container.php';
require 'Person.php';

use App\Car;
use App\Container;
use App\Leg;
use App\Train;
use App\Traveller;

//$tool = new Car();
//$traveller = new Traveller($tool);
//
//$traveller->visitTibet();

$app = new Container();
$app->bind('App\Visit', 'App\Train');
$app->bind('traveller', 'App\Traveller');
$traveller = $app->make('traveller');
$traveller->visitTibet();