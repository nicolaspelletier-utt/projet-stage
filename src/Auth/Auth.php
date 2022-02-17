<?php

namespace App\Auth;

class Auth
{
    public function hashGenerate(string $login, string $passwd) : string{
        $hash = hash('sha512', md5(htmlentities($login)).htmlentities($passwd));
        return $hash;
    }
}
