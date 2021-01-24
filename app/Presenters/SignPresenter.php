<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
Use App\Model\User\User;
use Nette\Application\UI\Form;

class SignPresenter extends BasePresenter{

    private $database;

    public function __construct(User $database)
    {
        $this->database = $database;
    }

    protected function createComponentSignInForm()
    {
        $form = new Form();

        $form->addText('username','Přihlašovací jméno::')
            ->setCaption('Zadejte přihlašovací jméno')
            ->setRequired();
        $form->addPassword('password','Heslo')
            ->setCaption('Zadejte heslo:')
            ->setRequired();
        $form->addSubmit('Potvrdit');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];

        return $form;
    }

    public function signInFormSucceeded(Form $form, $values)
    {
        $data = ['username' => $values->username, 'password' => $values->password];

        try {
            $this->getUser()->login($data);
            $this->redirect('Homepage:default');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->redirect('Homepage:default');
    }
}