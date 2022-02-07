<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Model;


class StatsController extends AbstractController {
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
   
    public function posts(Request $request) {
        $session = new Session();
        $session->start();
        if (!empty($session->get('logged'))) {
            $db=$this->model->getInstance();
            $query='';
            $statement=$db->prepare($query);
            $statement->execute();
            $result=$statement->fetchAll();
            $result_json=json_encode($result);
            $response = new Response($result_json,200, [
                "Content-Type" => "application/json"
            ]);
        }
        else {
            $response = new Response('Accès Interdit',403);
        }
        return $response;

    }
    public function comments(Request $request) {
        $session = new Session();
        $session->start();
        if (!empty($session->get('logged'))) {
            $db=$this->model->getInstance();
            $query='';
            $statement=$db->prepare($query);
            $statement->execute();
            $result=$statement->fetchAll();
            $result_json=json_encode($result);
            $response = new Response($result_json,200, [
                "Content-Type" => "application/json"
            ]);
        }
        else {
            $response = new Response('Accès Interdit',403);
        }
        return $response;

    }
    public function users(Request $request) {
        $session = new Session();
        $session->start();
        if (!empty($session->get('logged'))) {
            $db=$this->model->getInstance();
            $query='';
            $statement=$db->prepare($query);
            $statement->execute();
            $result=$statement->fetchAll();
            $result_json=json_encode($result);
            $response = new Response($result_json,200, [
                "Content-Type" => "application/json"
            ]);
        }
        else {
            $response = new Response('Accès Interdit',403);
        }
        return $response;

    } 
    public function nointerraction(Request $request) {
        $session = new Session();
        $session->start();
        if (!empty($session->get('logged'))) {
            $db=$this->model->getInstance();
            $query='';
            $statement=$db->prepare($query);
            $statement->execute();
            $result=$statement->fetchAll();
            $result_json=json_encode($result);
            $response = new Response($result_json,200, [
                "Content-Type" => "application/json"
            ]);
        }
        else {
            $response = new Response('Accès Interdit',403);
        }
        return $response;

    }
}