<?php

namespace App\Model;

use Exception;
use PDO;

class Model
{
    protected $db;

    public function getInstance(): PDO
    {
        try {
            $db = new PDO('mysql:host=api_db;dbname=training_project;charset=utf8', 'root', 'root');

            return $db;
        } catch (Exception $e) {
            exit('Erreur : '.$e->getMessage());
        }
    }

    public function execQuery(string $query, array $values): array
    {
        $statement = $this->getInstance()->prepare($query);
        $statement->execute($values);

        return $statement->fetchAll();
    }
}
