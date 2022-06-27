<?php

namespace App\Controllers;


use App\DBConnection;
use App\Request;
use PDO;
use PDOException;

require_once ('BaseController.php');
require_once ('DBConnection.php');

class LoginController extends BaseController
{
    /**
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        match ($request->getMethod()){
            'GET'=> $this->index($request),
            'POST'=>$this->login($request)
        };

    }
    /**
     * @var Request $request
     */
    public function index(Request $request)
    {
        if ($request->isLogin()){
            header('Location: /');
        } else {
            require_once ('views/login.phtml');
        }


    }
    private function login(Request $request)
    {
        $connection = new DBConnection();
        $sql = 'SELECT * FROM users WHERE login = :login';
        $query = $connection::$pdo->prepare($sql);
        try {
            $query->execute([
                'login' => $request->getData()['login']
            ]);
        $result = $query->fetch(PDO::FETCH_OBJ);
            if ($query->rowCount()>0 &&
            password_verify( $request->getData()['password'], $result->password)){
                $_SESSION['user_login']=$result->login;
                header('Location: /');
            } else {
                $_SESSION["alert"] = 'No such user';
                header('Location: /login');
            }
        } catch (PDOException $e) {
            $_SESSION["alert"] = $e->getMessage();
            header('Location: /login');
        }
    }
}