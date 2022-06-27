<?php

namespace App\Controllers;


use App\DBConnection;
use App\Request;
use PDO;
use PDOException;


class SearchByActorController extends BaseController
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
    /**
     * @var Request $request
     */
    public function index(Request $request){

        if ($request->isLogin() === true){

            $films = $this->getFilmbyActor($request);
            require_once ('views/index.phtml');
        } else{
            header('Location: /login');
        }
    }

    public function getFilmbyActor(Request $request):array{

        $connection = new DBConnection();
        $sql = 'SELECT * FROM actors WHERE full_name = :actor';
        $query = $connection::$pdo->prepare($sql);
        try {
            $query->execute([
                'actor' => $request->getData()['actor'],
            ]);
            if ($query->rowCount()>0){
                $actor_id = $query->fetch(PDO::FETCH_OBJ)->id;
            } else {
                $_SESSION["alert"] = 'No such actor';
                header('Location: /login');
            }
        } catch (PDOException $e) {
            $_SESSION["alert"] = $e->getMessage();
            header('Location: /login');
        }

        $sql = 'SELECT * FROM films_actors 
                        LEFT JOIN films ON id = film_id 
            WHERE films_actors.actor_id = :id';
        $query = $connection::$pdo->prepare($sql);
        $query->execute([
            "id"=>$actor_id
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);


    }
}