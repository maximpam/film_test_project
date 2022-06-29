<?php

namespace App\Controllers;

use App\DBConnection;
use App\Request;
use PDO;
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
            'POST' => $this->validate($request)
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
            $sql = 'INSERT INTO films (title, year, format) VALUES (:title, :year, :format)
                        ON DUPLICATE KEY UPDATE year = :year, format = :format';
            $query = $connection::$pdo->prepare($sql);
            try {
                $query->execute([
                    'title' => $request->getData()['Title'],
                    'year' => $request->getData()['ReleaseYear'],
                    'format' => $request->getData()['Format']
                ]);
                $film_id = $connection::$pdo->lastInsertId();
                if ($film_id == 0){
                    $sql = 'SELECT * FROM films WHERE title = :title';
                    $query = $connection::$pdo->prepare($sql);
                    $query->execute([
                        'title' => $request->getData()['Title']
                    ]);
                    $film_id = $query->fetch(PDO::FETCH_OBJ)->id;
                }
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
                $sql = 'SELECT * FROM films_actors WHERE film_id = :film_id AND actor_id = :actor_id';
                $query = $connection::$pdo->prepare($sql);
                $query->execute([
                    'film_id' => $film_id,
                    'actor_id' => $actor_id
                ]);
                if ($query->rowCount()===0){
                    $sql = 'INSERT INTO films_actors (film_id, actor_id) VALUES (:film_id, :actor_id)';
                    $query = $connection::$pdo->prepare($sql);
                    $query->execute([
                        'film_id' => $film_id,
                        'actor_id' => $actor_id
                    ]);
                }
            }
            header('Location: /');
        } else{
            header('Location: /login');
        }
    }
    /**
     * @var Request $request
     */

    private function validate(Request $request){
        if (empty(trim($request->getData()['Title']))||
            empty(trim($request->getData()['ReleaseYear']))||
            empty(trim($request->getData()['Format']))||
            empty(trim($request->getData()['Stars']))){
            $_SESSION['alert']='Fields shouldn`t be empty';
            header('Location: /addfilm');
        }elseif($request->getData()['ReleaseYear']<1850 || $request->getData()['ReleaseYear']>2022){
            $_SESSION['alert']='Release Year should be between 1850 and 2022';
            header('Location: /addfilm');

        }else{
            $this->save($request);
        }
    }
}