<?php

namespace App\Model;

use Exception;
use PDO;

class Database {
    private static $instance=null;
    private static $db;
    private function __construct() {
        try {
            $db = new PDO('mysql:host=api_db;dbname=training_project;charset=utf8', 'root', 'root');

            self::$db = $db;
        } catch (Exception $e) {
            exit('Erreur : '.$e->getMessage());
        }
    }
    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new Database();  
          }
      
          return self::$instance::$db;
    }
}