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
    protected $session;
    protected $auth;

    public function __construct(model $model, RequestStack $requestStack, Param $paramFunctions, Auth $auth)
    {
        $this->model = $model;
        $this->requestStack = $requestStack;
        $this->param = $paramFunctions;
        $this->session = $requestStack->getSession();
        $this->auth = $auth;
    }

    public function isLogged()
    {
        $array = $this->auth->setLogged($this->session);
        return $this->param->successResponse($array);

    }

    public function login(Request $request, Auth $auth, Param $paramFunctions)
    {
        if (!$auth->isLogged($this->session)) {
            $query = "select id from authentification where hash=?";
            $results = $this->model->execQuery($query,[$auth->hashGenerate(json_decode($request->getContent(),true)['login'],json_decode($request->getContent(),true)['passwd'])]);
            if (0 != count($results)) {
                $auth->setSuccesslogin($this->session);
            }
        }
        $array = $auth->setLogged($this->session);
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
