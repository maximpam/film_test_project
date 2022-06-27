<?php

namespace App;



class App
{
    public function run():void
    {
        $request = new Request();
        $router = new Router($request);

}

}