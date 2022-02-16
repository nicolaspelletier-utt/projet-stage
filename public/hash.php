<?php

$login = 'root';
$passwd = 'root';
$login_md5 = md5($login);
$hash = hash('sha512', $login_md5.$passwd);
echo $hash;
