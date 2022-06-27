<?php

namespace App\Controllers;

use App\DBConnection;
use App\Request;
use PDOException;

class FilmAddController extends BaseController
{
    /**
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        match ($request->getMethod()){
            'GET'=> $this->index($request),
            'POST' => $this->save($request)
        };


    }

    public function index(Request $request){

        if ($request->isLogin() === true){

            require_once ('views/filmadd.phtml');
        } else{
            header('Location: /login');
        }

    }
    /**
     * @var Request $request
     */

    public static function save(Request $request){
        if ($request->isLogin() === true){
            $connection = new DBConnection();
            $sql = 'INSERT INTO films (title, year, format) VALUES (:title, :year, :format)';
            $query = $connection::$pdo->prepare($sql);
            try {
                $query->execute([
                    'title' => $request->getData()['Title'],
                    'year' => $request->getData()['ReleaseYear'],
                    'format' => $request->getData()['Format']
                ]);
                $film_id = $connection::$pdo->lastInsertId();

            } catch (PDOException $e) {
                $_SESSION["alert"] = $e->getMessage();
                header('Location: /register');
            }
            $actors = $request->getData()['Stars'];
            $actors = explode(", ", $actors);
            foreach ($actors as $actor){
                $sql = 'SELECT * FROM actors WHERE full_name = :full_name';
                $query = $connection::$pdo->prepare($sql);
                $query->execute([
                    'full_name' => $actor
                ]);
                if ($query->rowCount()===0){
                    $sql = 'INSERT INTO actors (full_name) VALUES (:full_name)';
                    $query = $connection::$pdo->prepare($sql);
                    $query->execute([
                        'full_name' => $actor
                    ]);
                    $actor_id = $connection::$pdo->lastInsertId();
                } else {
                    $actor_id = $query->fetch(PDO::FETCH_OBJ)->id;
                }
                $sql = 'INSERT INTO films_actors (film_id, actor_id) VALUES (:film_id, :actor_id)';
                $query = $connection::$pdo->prepare($sql);
                $query->execute([
                    'film_id' => $film_id,
                    'actor_id' => $actor_id
                ]);

            }
            header('Location: /');
        } else{
            header('Location: /login');
        }



    }
}