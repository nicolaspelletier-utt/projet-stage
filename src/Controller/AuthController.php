<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Model;

class AuthController extends AbstractController {
    protected $model;

    public function __construct(model $model) 
    {
        $this->model = $model;        
    }
    public function login(Request $request) {
        $session = new Session();
        $session->start();
        if (!empty($session->get('logged'))) {
            if ($request->request->has('login')&&$request->request->has('passwd')) {
                $db=$this->model->getInstance();
                $hash=hash('sha512',md5(htmlentities($request->request->get('login'))) . htmlentities($request->request->get('passwd')));
                $query="select id from authentification where hash=?";
                $statement=$db->prepare($hash);
                $statement->execute();
                $results = $statement->fetchAll();
                if (count($results)!=0) {
                    $session->set('logged',true);
                }    
            }
            if (!empty($session->get('logged'))) {
                $array=array('logged',true);
            }
            else {
                $array=array('logged',false);
            }
        }
        else {
            $array=array('logged',true);
        }
        $array_json=json_encode($array);
        $response = new Response($array_json,200,[
            "Content-Type" => "application/json"
        ]);
        return $response;
        

        
    }
}