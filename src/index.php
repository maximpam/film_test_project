<?php

use App\App;
session_start();
require_once ('vendor/autoload.php');



$app = new App();
$app->run();

