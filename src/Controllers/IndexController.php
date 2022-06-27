<?php

namespace App\Controllers;
use App\Request;

class IndexController extends BaseController
{
    /**
     * @var Request $request
     */
    public function __construct(Request $request)
    {
         match ($request->getMethod()){
            'GET'=> $this->index($request)
        };


    }

    public function index($request){

        if ($request->isLogin() === true){
            $films = FilmController::getFilms($request);
            require_once ('views/index.phtml');
        } else{
            header('Location: /login');
        }
    }
}