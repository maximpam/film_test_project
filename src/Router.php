<?php
namespace App;

use App\Controllers\BaseController;
use App\Controllers\FileUploadController;
use App\Controllers\FilmAddController;
use App\Controllers\FilmController;
use App\Controllers\FilmDeleteController;
use App\Controllers\IndexController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\RegisterController;
use App\Controllers\SearchByActorController;
use App\Controllers\SearchByNameController;

class Router {

    /**
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        $this->getController($request);
    }
    /**
     * @var Request $request
     */
    protected function getController(Request $request):BaseController{
        $className = match ($request->getUrl()){
            '/' => IndexController::class,
            '/login' => LoginController::class,
            '/register' => RegisterController::class,
            '/logout'   => LogoutController::class,
            '/addfilm' => FilmAddController::class,
            '/film'=> FilmController::class,
            '/filmdelete' => FilmDeleteController::class,
            '/searchbyactor' => SearchByActorController::class,
            '/searchbyname' => SearchByNameController::class,
            '/upload' =>  FileUploadController::class,
            default => IndexController::class
        };

        return new $className($request);
    }
}
