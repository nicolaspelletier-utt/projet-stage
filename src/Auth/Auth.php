<?php

namespace App\Auth;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class Auth
{
    public function hashGenerate(string $login, string $passwd) : string{
        $hash = hash('sha512', md5(htmlentities($login)).htmlentities($passwd));
        return $hash;
    }
    public function setLogged(Session $session) : array {
        if ($session->has('logged')) {
            $array['logged'] = true;
        } else {
            $array['logged'] = false;
        }
        return $array;
    }
    public function isLogged(Session $session) : bool {
        if ($session->has('logged')) {
            return true;
        } else {
            return false;
        }
    }
    public function setSuccesslogin(Session $session) : void {
        $session->set('logged',true);
    }
    public function setNotLogged(Session $session) : array {
        if ($session->has('logged')) {
            $array['notLogged'] = false;
        } else {
            $array['notLogged'] = true;
        }
        return $array;
    }
}
