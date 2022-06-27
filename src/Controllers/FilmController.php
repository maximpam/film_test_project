<?php

namespace App\Controllers;


use App\DBConnection;
use App\Request;
use PDO;


class FilmController extends BaseController
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
            $films = $this->getFilmbyId($request);
            require_once ('views/film.phtml');
        } else{
            header('Location: /login');
        }
    }

    public static function getFilms(Request $request){
        if (empty($request->getData())){
            $connection = new DBConnection();
            $sql = 'SELECT id, title FROM films ORDER BY films.title ASC';
            $query = $connection::$pdo->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        }else{

           return $_SESSION["alert"] = 'No such user';
        }

    }
    public function getFilmbyId(Request $request):array{
            $connection = new DBConnection();
            $sql = 'SELECT * FROM films 
                    LEFT JOIN films_actors 
                        ON films.id = films_actors.film_id 
                    LEFT JOIN actors ON actors.id = films_actors.actor_id 
                    WHERE films_actors.film_id=:id';
            $query = $connection::$pdo->prepare($sql);
            $query->execute([
                "id"=>$request->getData()['id']
            ]);
            return $query->fetchAll(PDO::FETCH_OBJ);


    }
}