<?php
namespace App\Controllers;
use App\Request;

//require_once ('BaseController.php');
//require_once ('FilmAddController.php');

class FileUploadController extends BaseController
{
    /**
     * @var Request $request
     */
    public function __construct(Request $request)
    {
        match ($request->getMethod()){
            'GET'=> $this->index($request),
            'POST'=>$this->upload($request)
        };

    }
    /**
     * @var Request $request
     */
    public function index(Request $request)
    {
        if ($request->isLogin() === true){
            require_once ('views/upload.phtml');
        } else {
            header('Location: /');
        }

    }
    private function upload(Request $request)
    {
    if ($request->isLogin() === true){
        $file = file_get_contents($_FILES['file']['tmp_name']);
        $file = explode("\n\n",$file);
        function array_delete(array $array, array $symbols = array('')):array
        {
            return array_diff($array, $symbols);
        }
        $file = array_delete($file,[null]);
        $result_array = [];
        foreach ($file as $elements){
            $elements=explode("\n", $elements);
            $buffer_array = [];
            foreach ($elements as $element){
                $element = explode(":", $element);
                $element[0] = str_replace(' ', '', $element[0]);
                $element[1] = trim($element[1]);
                $buffer_array[$element[0]] = $element[1];
            }
            $result_array[]=$buffer_array;
        }
        foreach ($result_array as $item){
            $request->setData($item);
            FilmAddController::save($request);
        }
    } else {
        header('Location: /');
    }

}

}