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
            if ($request->query->has('beggin') && $request->query->has('end')) {
                $begin=$request->query->get('begin');
                $end=$request->query->get('end');
                $query= 'select avg(de.value) from (select count(c.comment_id) as value from data_comments where created_time between ? and ? c group by c.post_id) de  ';
                $statement=$db->prepare($query);
                $statement->execute(array(htmlspecialchars($begin),htmlspecialchars($end)));
            }
            else {
                $query='select avg(de.value) from (select count(c.comment_id) as value from data_comments c group by c.post_id) de ';
                $statement=$db->prepare($query);
                $statement->execute();
            }

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
            if ($request->query->has('beggin') && $request->query->has('end')) {
                $begin=$request->query->get('begin');
                $end=$request->query->get('end');
                $query='select u.people_name, count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id and p.created_time between ? and ? group by u.people_id order by  count(p.post_id) DESC limit 10 ';
                $statement=$db->prepare($query);
                $statement->execute(array(htmlspecialchars($begin),htmlspecialchars($end)));
            }
            else {
                $query='select u.people_name, count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id group by u.people_id order by  count(p.post_id) DESC limit 10 ';
                $statement=$db->prepare($query);
                $statement->execute();
            }
         
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
            if ($request->query->has('beggin') && $request->query->has('end')) {
                $begin=$request->query->get('begin');
                $end=$request->query->get('end');
                $query="select distinct dp.people_name from data_people dp where dp.people_id not in (select r.people_id from data_reactions r where r.created_time between '2021-07-01 15:02:00' and '2021-07-31 15:02:30') and dp.people_id not in (select c.people_id from data_comments c where c.created_time between ? and ?) limit 10";
                $statement=$db->prepare($query);
                $statement->execute(array(htmlspecialchars($begin),htmlspecialchars($end)));
            }
            else {
                $query="select distinct dp.people_name from data_people dp where dp.people_id not in (select r.people_id from data_reactions r ) and dp.people_id not in (select c.people_id from data_comments c ) limit 10";
                $statement=$db->prepare($query);
                $statement->execute();
            }
            
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