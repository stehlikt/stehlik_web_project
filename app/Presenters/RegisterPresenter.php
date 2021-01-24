<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\User\User;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;


class RegisterPresenter extends BasePresenter{

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

        $form->addText('email', 'E-mail:')
            ->setCaption('Zadejte svuj email.')
            ->setRequired();
        $form->addText('username','Přihlašovací jméno:')
            ->setCaption('Zadejte přihlašovací jméno')
            ->setRequired();
        $form->addPassword('password','Heslo:')
            ->setCaption('Zadejte heslo')
            ->setRequired();
        $form->addSubmit('Hotov');

        $form->onSuccess[] = [$this, 'registerFormSucceeded'];

        return $form;

    }

    public function registerFormSucceeded(Form $form, $values)
    {

        $this->user->insertUser([
            'username' => $values->username,
            'email' => $values->email,
            'password' => $this->password->hash($values->password),
            'role_id' => 2
        ]);

        $this->flashMessage('Registrace proběhla v pořádku');
        $this->redirect('Homepage:default');

    }
}
