<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class TableController extends AbstractController
{
    public function getTable(Request $request)
    {
        $session = new Session();
        $session->start();
        if (!empty($session->get('logged'))) {
            $db = $this->model->getInstance();
            $query = '';
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $result_json = json_encode($result);
            $response = new Response($result_json, 200, [
                'Content-Type' => 'application/json',
            ]);
        } else {
            $response = new Response('Accès Interdit', 403);
        }

        return $response;
    }
}
