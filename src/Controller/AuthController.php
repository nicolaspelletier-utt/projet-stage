<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Model;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthController extends AbstractController {
    protected $model;
    protected $requestStack;

    public function __construct(model $model, RequestStack $requestStack) 
    {
        $this->model = $model;   
        $this->requestStack = $requestStack;   
    }
    public function login(Request $request) {
        $session = $this->requestStack->getSession();
        $json=$request->getContent();
        $jsondecode=json_decode($json,true);
        $login=$jsondecode['login'];
        $passwd=$jsondecode['passwd'];
        if (!$session->has('logged')) {
                $db=$this->model->getInstance();
                $hash=hash('sha512',md5(htmlentities($login)) . htmlentities($passwd));
                $query="select id from authentification where hash='" . $hash . "'";
                $statement=$db->prepare($query);
                $statement->execute();
                $results = $statement->fetchAll();
                if (count($results)!=0) {
                    $session->set('logged',true);
                }                    
            if ($session->has('logged')) {
                $array['logged']=true;
            }
            else {
                //Echec d'authentification
                $array['logged']=false;
            }
        }
        else {
            //Utilisateur déjà log
            $array['logged']=true;
        }
        return $this->json($array,200);
        //$array_json=json_encode($array);
        

        
    }
    public function Logout(Request $request) {
        $session = $this->requestStack->getSession();
        $session->remove('logged');
        $session->clear();
        $session = $this->requestStack->getSession();
        $response = new Response('Success',200);
        $response->headers->clearCookie('PHPSESSID');
        $session->invalidate();
        session_destroy();
        return $response;
    }
    
}