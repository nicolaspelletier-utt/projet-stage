<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController{

    public function home(Request $request) : RedirectResponse {
        $session = new Session();
        $session->start();
        if (empty($session->get('logged'))) { //Si on est pas log 
            return $this->redirectToRoute('login');
        }
        else { //Si on est log
            dd("Tu es bien log, bravo");
        }
        
    }
    public function login(Request $request) {
        dd("Connecte toi");
    }


}