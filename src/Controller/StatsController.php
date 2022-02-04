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
    public function display(Request $request) {
        $session = new Session();
        $session->start();
        $db=$this->model->getInstance();
        if (empty($session->get('logged'))) { //Si on est pas log 
            return $this->redirectToRoute('login');
        }
        else { //Si on est log
  
            $statement=$db->prepare('SELECT * FROM data_posts LIMIT 10');
            $statement->execute();
            $result=$statement->fetchAll();
            dd($result);
        }
    }
}