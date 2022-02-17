<?php

namespace App\Controller;

use App\Model\Model;
use App\Param\Param;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends AbstractController
{
    protected $model;
    protected $requestStack;

    public function __construct(Model $model, RequestStack $requestStack)
    {
        $this->model = $model;
        $this->requestStack = $requestStack;
    }

    public function posts(Request $request, Param $paramFunctions)
    {
        $session = $this->requestStack->getSession();
        if ($session->has('logged')) {
            $getParam = $paramFunctions->getArray($request);
            if ($paramFunctions->hasDateScope($request) && !$paramFunctions->hasFilter($request)) {
                $query = 'select e.name, e.id, count(p.post_id) as number from data_posts p, data_entities e where e.id =p.entity_id and p.created_time between ? and ? group by p.entity_id order by count(p.post_id) desc';
                $param = [$getParam['begin'], $getParam['end']];
            } else if (!$paramFunctions->hasDateScope($request) && !$paramFunctions->hasFilter($request)) {
                $query = 'select e.name, e.id, count(p.post_id) as number from data_posts p, data_entities e where e.id =p.entity_id group by p.entity_id order by count(p.post_id) desc ';
                $param = [];
            } else if ($paramFunctions->hasDateScope($request) && $paramFunctions->hasFilter($request)) {
                $query='select e.name, e.id, count(p.post_id) as number from data_posts p, data_entities e where e.name like ? and e.id =p.entity_id and p.created_time between ? and ? group by p.entity_id order by count(p.post_id) desc';
                $param = [ "%" . $getParam['search'] . "%",$getParam['begin'], $getParam['end']];
            } else {
                $query='select e.name, e.id, count(p.post_id) as number from data_posts p, data_entities e where e.name like ? and e.id =p.entity_id group by p.entity_id order by count(p.post_id) desc';
                $param = ["%" . $getParam['search'] . "%"];
            }
            $result = $this->model->execQuery($query,$param);

            $array = [];
            foreach ($result as $key => $value) {
                $array[$key]['id'] = $key + 1;
                $array[$key]['name'] = $value['name'];
                $array[$key]['count'] = $value['number'];
            }
        } else {
            $array = ['notLogged' => true];
        }

        return $paramFunctions->successResponse($array);
    }

    public function comments(Request $request, Param $paramFunctions)
    {
        $session = $this->requestStack->getSession();
        if ($session->has('logged')) {
            $getParam = $paramFunctions->getArray($request);
            if ($paramFunctions->hasDateScope($request)) {
                $query = 'select avg(de.value) from (select count(c.comment_id) as value from data_comments c where c.created_time between ? and ? group by c.post_id) de  ';
                $param = [$getParam['begin'],$getParam['end']];
            } else {
                $query = 'select avg(de.value) from (select count(c.comment_id) as value from data_comments c group by c.post_id) de ';
                $param = [];
            }

            $result = $this->model->execQuery($query,$param);
            $array = [
                'comments' => $result['0']['0'],
            ];
        } else {
            $array = ['notLogged' => true];
        }

        return $paramFunctions->successResponse($array);

    }

    public function users(Request $request, Param $paramFunctions)
    {
        $session = $this->requestStack->getSession();

        if ($session->has('logged')) {
            $getParam = $paramFunctions->getArray($request);
            if ($paramFunctions->hasDateScope($request)) {
                $query = 'select u.people_name, u.people_id, count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id and p.created_time between ? and ? group by u.people_id order by  count(p.post_id) DESC limit 10 ';
                $param = [$getParam['begin'],$getParam['end']];
            } else {
                $query = 'select u.people_name, u.people_id, count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id group by u.people_id order by  count(p.post_id) DESC limit 10 ';
                $param = [];
            } 
            $result = $this->model->execQuery($query,$param);
            $array = [];
            foreach ($result as $key => $value) {
                if ($paramFunctions->hasDateScope($request)) {
                    $query = 'select count(r.post_id) from data_reactions r where r.people_id=? and created_time between ? and ?';
                    $query2 = 'select count(c.comment_id) from data_comments c where c.people_id=? and created_time between ? and ?';
                    $param = [$value['1'],$getParam['begin'],$getParam['end']];
                } else {
                    $query = 'select count(r.post_id) from data_reactions r where r.people_id=?';
                    $query2 = 'select count(c.comment_id) from data_comments c where c.people_id=?';
                    $param = [$value['1']];
                }
                $result = $this->model->execQuery($query,$param);
                $result2 = $this->model->execQuery($query2,$param);
                $array[$key]['id'] = $key + 1;
                $array[$key]['name'] = $value['0'];
                $array[$key]['count'] = $value['2'];
                $array[$key]['reactions'] = $result['0']['0'];
                $array[$key]['comments'] = $result2['0']['0'];
            }
        } else {
            $array = ['notLogged' => true];
        }

        return $paramFunctions->successResponse($array);
    }

    public function nointerraction(Request $request,Param $paramFunctions)
    {
        $session = $this->requestStack->getSession();

        if ($session->has('logged')) {
            $getParam = $paramFunctions->getArray($request);
            if ($paramFunctions->hasDateScope($request) && !$paramFunctions->hasFilter($request)) {
                $query = "select distinct dp.people_name from data_people dp where dp.people_id not in (select r.people_id from data_reactions r where r.created_time between ? and ?) and dp.people_id not in (select c.people_id from data_comments c where c.created_time between ? and ?) limit 10";
                $param = [$getParam['begin'],$getParam['end'],$getParam['begin'],$getParam['end']];
            } else if (!$paramFunctions->hasDateScope($request) && !$paramFunctions->hasFilter($request)) {
                $query = 'select distinct dp.people_name from data_people dp where dp.people_id not in (select r.people_id from data_reactions r ) and dp.people_id not in (select c.people_id from data_comments c ) limit 10';
                $param = [];
            } else if ($paramFunctions->hasDateScope($request) && $paramFunctions->hasFilter($request)) {
                $query='select distinct dp.people_name from data_people dp where dp.people_name like ? and dp.people_id not in (select r.people_id from data_reactions r where r.created_time between ? and ?) and dp.people_id not in (select c.people_id from data_comments c where c.created_time between ? and ?) limit 10';
                $param = ["%" . $getParam['search'] . "%",$getParam['begin'],$getParam['end'],$getParam['begin'],$getParam['end']];
            } else {
                $query='select distinct dp.people_name from data_people dp where dp.people_name like ? and dp.people_id not in (select r.people_id from data_reactions r ) and dp.people_id not in (select c.people_id from data_comments c ) limit 10';
                $param = ["%" . $getParam['search'] . "%"];
            }
            $result = $this->model->execQuery($query,$param);
            $array = [];
            foreach ($result as $key => $value) {
                $array[$key]['id'] = $key + 1;
                $array[$key]['name'] = $value['0'];
            }
        } else {
            $array = ['notLogged' => true];
        }
        return $paramFunctions->successResponse($array);
    }

    public function usersNoLimit(Request $request, Param $paramFunctions)
    {
        $session = $this->requestStack->getSession();

        if ($session->has('logged')) {
            $getParam = $paramFunctions->getArray($request);
            if ($paramFunctions->hasDateScope($request) && !$paramFunctions->hasFilter($request)) {
                $query = 'select u.people_name, u.people_id,count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id and p.created_time between ? and ? group by u.people_id order by  count(p.post_id) DESC  ';
                $param = [$getParam['begin '],$getParam['end']];
            } else if (!$paramFunctions->hasDateScope($request) && !$paramFunctions->hasFilter($request)) {
                $query = 'select u.people_name,u.people_id, count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id group by u.people_id order by  count(p.post_id) DESC  ';
                $param = [];
            } else if ($paramFunctions->hasDateScope($request) && $paramFunctions->hasFilter($request)) {
                $query = 'select u.people_name, u.people_id, count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id and p.created_time between ? and ? and u.people_name like ? group by u.people_id order by  count(p.post_id) DESC ';
                $param = [$getParam['begin'],$getParam['end'],"%" . $getParam['search'] . "%"];
            } else {
                $query = 'select u.people_name, u.people_id, count(p.post_id) from data_people u, data_posts p where u.people_id = p.people_id and u.people_name like ? group by u.people_id order by  count(p.post_id) DESC  ';
                $param = ["%" . $getParam['search'] . "%"];
            }
            $result = $this->model->execQuery($query,$param);
            $array = [];
            foreach ($result as $key => $value) {
                if ($paramFunctions->hasDateScope($request)) {
                    $query = 'select count(r.post_id) from data_reactions r where r.people_id=? and created_time between ? and ?';
                    $query2 = 'select count(c.comment_id) from data_comments c where c.people_id=? and created_time between ? and ?';
                    $param = [$value['1'],$getParam['begin'],$getParam['end']];
                } else {
                    $query = 'select count(r.post_id) from data_reactions r where r.people_id=?';
                    $query2 = 'select count(c.comment_id) from data_comments c where c.people_id=?';
                    $param = [$value['1']];
                }
                $result = $this->model->execQuery($query,$param);
                $result2 = $this->model->execQuery($query2,$param);
                $array[$key]['id'] = $key + 1;
                $array[$key]['name'] = $value['0'];
                $array[$key]['count'] = $value['2'];
                $array[$key]['reactions'] = $result['0']['0'];
                $array[$key]['comments'] = $result2['0']['0'];
            }
        } else {
            $array = ['notLogged' => true];
        }
        return $paramFunctions->successResponse($array);

    }

    public function unresponsive(Request $request, Param $paramFunctions)
    {
        $session = $this->requestStack->getSession();

        if ($session->has('logged')) {
            $getParam = $paramFunctions->getArray($request);
            if ($paramFunctions->hasDateScope($request)) {
                $query = 'select u.people_name, u.people_id, count(r.post_id) from data_people u, data_reactions r where u.people_id = r.people_id and created_time between ? and ?group by u.people_id order by  count(r.post_id) ASC limit 10  ';
                $param = [$getParam['begin'],$getParam['end']];
            } else {
                $query = 'select u.people_name, u.people_id, count(r.post_id) from data_people u, data_reactions r where u.people_id = r.people_id group by u.people_id order by  count(r.post_id) ASC limit 10 ';
                $param = [];
            }

            $result = $this->model->execQuery($query,$param);
            $array = [];
            foreach ($result as $key => $value) {
                if ($paramFunctions->hasDateScope($request)) {
                    $query = 'select count(p.post_id) from data_posts p where p.people_id=? and created_time between ? and ?';
                    $query2 = 'select count(c.comment_id) from data_comments c where c.people_id=? and created_time between ? and ?';
                    $param = [$value['1'],$getParam['begin'],$getParam['end']];
                } else {
                    $query = 'select count(p.post_id) from data_posts p where p.people_id=?';
                    $query2 = 'select count(c.comment_id) from data_comments c where c.people_id=?';
                    $param = [$value['1']];
                }
                $result = $this->model->execQuery($query,$param);
                $result2 = $this->model->execQuery($query2,$param);
                $array[$key]['id'] = $key + 1;
                $array[$key]['name'] = $value['0'];
                $array[$key]['reactions'] = $value['2'];
                $array[$key]['posts'] = $result['0']['0'];
                $array[$key]['comments'] = $result2['0']['0'];
            }
        } else {
            $array = ['notLogged' => true];
        }

        return $paramFunctions->successResponse($array);

    }
}
