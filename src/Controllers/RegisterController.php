<?php

namespace App\Controllers;

use App\Request;


class RegisterController extends BaseController
{
    /**
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        match ($request->getMethod()){
            'GET'=> $this->index($request),
            'POST'=> $this->save($request)
        };

    }

    public function index($request){

        if ($request->isLogin()){
            header('Location: /');
        }
        require_once ('views/register.phtml');

    }
    /**
     * @var Request $request
     */
    private function save(Request $request){

        if ($request->getData()['password'] !== $request->getData()['$confirmed_password']){
            $_SESSION["alert"] = "Password doesn`t match";
            header('Location: /register');
        }
        else {
            $connection = new DBConnection();
            $sql = 'INSERT INTO users(login, password) VALUES (:login,:password)';
            $query = $connection::$pdo->prepare($sql);
            try {
                $query->execute([
                    'login' => $request->getData()['login'],
                    'password' => $request->getData()['password']
                ]);
                header('Location: /');
            } catch (PDOException $e) {
                $_SESSION["alert"] = $e->getMessage();
                header('Location: /register');
            }


        }


    }
}