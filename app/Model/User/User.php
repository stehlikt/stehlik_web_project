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

    public function getAllUsers()
    {
        return $this->database->query('SELECT * FROM users')->fetchAll();
    }

    public function getUserByUsername($username)
    {
        return $this->database->query('SELECT * FROM users WHERE username = ?',$username);
    }

    public function insertUser($user)
    {
        $this->database->query('INSERT INTO users',$user);
    }

    public function deleteUser($id)
    {
        $this->database->query('DELETE FROM users WHERE id = ?',$id);
    }

}