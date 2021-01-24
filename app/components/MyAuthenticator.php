<?php

declare(strict_types=1);

namespace App\components;

use Nette;
use Nette\Security\SimpleIdentity;

class MyAuthenticator implements Nette\Security\Authenticator
{
    private $database;
    private $passwords;

    public function __construct(
        Nette\Database\Explorer $database,
        Nette\Security\Passwords $passwords
    ) {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    public function authenticate(string $username, string $password): Nette\Security\IIdentity
    {
        $row = $this->database->table('users')
            ->where('username', $username)
            ->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('User not found.');
        }

        if (!$this->passwords->verify($password, $row->password)) {
            throw new Nette\Security\AuthenticationException('Invalid password.');
        }

        return new SimpleIdentity(
            $row->id,
            $row->role_id,
            ['name' => $row->username]
        );
    }
}