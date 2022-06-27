<?php

namespace App\Controllers;


use App\DBConnection;
use App\Request;
use PDO;
use PDOException;

require_once ('BaseController.php');

class SearchByNameController extends BaseController
{
    /**
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        match ($request->getMethod()){
            'POST'=> $this->index($request)
        };
    }
    public function index($request){

        if ($request->isLogin() === true){
            $films = $this->getFilmByName($request);
            require_once ('views/index.phtml');
        } else{
            header('Location: /login');
        }
    }

    public function getFilmByName(Request $request):array{

        $connection = new DBConnection();
        $sql = 'SELECT * FROM films WHERE title = :name';
        $query = $connection::$pdo->prepare($sql);
        try {
            $query->execute([
                'name' => $request->getData()['name'],
            ]);
            if ($query->rowCount()>0){
                $result =  $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                $_SESSION["alert"] = 'No such film';
                header('Location: /');
            }
        } catch (PDOException $e) {
            $_SESSION["alert"] = $e->getMessage();
            header('Location: /');
        }
        return $result;

    }
}