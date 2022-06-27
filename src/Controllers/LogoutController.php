<?php
namespace App\Controllers;


use App\Request;

class LogoutController extends BaseController
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

    /**
     * @var Request $request
     */
    public function index(Request $request)
    {

        if ($request->isLogin()){

            unset ($_SESSION['user_login']);
            header('Location: /');
        } else {
            header('Location: /login');
        }


    }
}