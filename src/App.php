<?php

namespace App;

require_once ('Request.php');
require_once ('Router.php');
class App
{
    public function run():void
    {
        $request = new Request();
        $router = new Router($request);

}

}