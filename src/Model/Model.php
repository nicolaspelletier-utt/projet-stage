<?php

namespace App\Model;

use Exception;
use PDO;

class Model {
    public function getInstance() : PDO {
        try
        {
            $db = new PDO('mysql:host=api_db;dbname=training_project;charset=utf8', 'root', 'root');
            return $db;
        }
        catch (Exception $e)
        {
                die('Erreur : ' . $e->getMessage());
        }
    }
}