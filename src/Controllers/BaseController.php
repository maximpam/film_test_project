<?php

namespace App\Controllers;

use App\Request;

abstract class BaseController
{
    abstract public function index(Request $request);
}