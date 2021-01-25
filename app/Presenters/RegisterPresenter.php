<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\User\User;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;


class RegisterPresenter extends BasePresenter
{

    private $user;
    private $password;

    public function __construct(User $user, Passwords $passwords)
    {
        $this->user = $user;
        $this->password = $passwords;
    }

    protected function createComponentRegisterForm(): Form
    {
        $form = new Form;

        $form->addText('email', 'Zadejte svuj email: ')
            ->setRequired('Vyplňte prosím svůj email');
        $form->addText('username', 'Zadejte přihlašovací jméno: ')
            ->setRequired('Vyplňte prosím své přihlašovací jméno');
        $form->addPassword('password', 'Zadejte heslo:')
            ->setRequired('Zadejte prosím heslo');
        $form->addSubmit('Hotov');

        $form->onSuccess[] = [$this, 'registerFormSucceeded'];

        return $form;
    }

    public function registerFormSucceeded(Form $form, $values)
    {
        if ($this->user->checkEmail($values->email)) {
            $this->flashMessage('Email je již obsazený');
        } elseif ($this->user->checkUsername($values->username)) {
            $this->flashMessage('Uživatelské jméno je již obsazeno');
        } else {
            $this->user->insertUser([
                'username' => $values->username,
                'email' => $values->email,
                'password' => $this->password->hash($values->password),
                'role' => 'user'
            ]);

            $this->flashMessage('Registrace proběhla v pořádku');
            $this->redirect('Homepage:default');
        }
    }
}
