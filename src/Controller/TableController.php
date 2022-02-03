<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class TableController extends AbstractController {

    public function display(Request $request) {
        $session = new Session();
        $session->start();
        if (empty($session->get('logged'))) { //Si on est pas log 
            return $this->redirectToRoute('login');
        }
        else { //Si on est log
            dd("Tu es bien log, bravo");
        }
    }
}