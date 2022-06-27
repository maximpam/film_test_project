<?php

namespace App\Controllers;

use App\DBConnection;
use App\Request;

require_once ('BaseController.php');

class FilmDeleteController extends BaseController
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
            $this->deleteFilmbyId($request);
            header('Location: /');
        } else{
            header('Location: /login');
        }
    }

    private function deleteFilmbyId(Request $request):void{
        $connection = new DBConnection();
        $sql = 'DELETE FROM films_actors WHERE films_actors.film_id = :id';
        $query = $connection::$pdo->prepare($sql);
        $query->execute([
            "id"=>$request->getData()['id']
        ]);
        $sql = 'DELETE FROM films WHERE id = :id';
        $query = $connection::$pdo->prepare($sql);
        $query->execute([
            "id"=>$request->getData()['id']
        ]);
    }
}