<?php

namespace App\Model\User;

use Nette;
use Nette\Security\SimpleIdentity;

class User{

    private $database;

    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
    }

    public function insertUser($user)
    {
        $this->database->query('INSERT INTO users',$user);
    }

}