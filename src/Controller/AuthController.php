<?php

namespace App\Controller;

use App\Model\Model;
use App\Auth\Auth;
use App\Param\Param;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    protected $model;
    protected $requestStack;
    protected $param;

    public function __construct(model $model, RequestStack $requestStack, Param $paramFunctions)
    {
        $this->model = $model;
        $this->requestStack = $requestStack;
        $this->param = $paramFunctions;
    }

    public function isLogged()
    {
        $session = $this->requestStack->getSession();
        if ($session->has('logged')) {
            $array['logged'] = true;
        } else {
            $array['logged'] = false;
        }
        return $this->param->successResponse($array);

    }

    public function login(Request $request, Auth $auth, Param $paramFunctions)
    {
        $session = $this->requestStack->getSession();
        $json = $request->getContent();
        if (!$session->has('logged')) {
            $query = "select id from authentification where hash=?";
            $results = $this->model->execQuery($query,[$auth->hashGenerate(json_decode($json,true)['login'],json_decode($json,true)['passwd'])]);
            if (0 != count($results)) {
                $session->set('logged', true);
            }
            if ($session->has('logged')) {
                $array['logged'] = true;
            } else {
                //Echec d'authentification
                $array['logged'] = false;
            }
        } else {
            //Utilisateur déjà log
            $array['logged'] = true;
        }

        return $paramFunctions->successResponse($array);

    }

    public function Logout()
    {
        $session = $this->requestStack->getSession();
        $session->clear();
        $response = new Response('Success', 200,[
            'Access-Control-Allow-Origin' => 'localhost:3000',
            'Access-Control-Allow-Credentials' => 'true'
        ]);
        return $response;
    }
}
