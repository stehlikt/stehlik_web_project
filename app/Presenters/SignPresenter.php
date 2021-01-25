<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\User\User;
use Nette\Application\UI\Form;

class SignPresenter extends BasePresenter
{

    private $database;

    public function __construct(User $database)
    {
        $this->database = $database;
    }

    protected function createComponentSignInForm()
    {
        $form = new Form();

        $form->addText('username', 'Zadejte přihlašovací jméno:')
            ->setRequired('Vyplňte prosím přihlašovací jméno');
        $form->addPassword('password', 'Zadejte heslo:')
            ->setRequired('Vyplňte prosím heslo');
        $form->addSubmit('Potvrdit');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];

        return $form;
    }

    public function signInFormSucceeded(Form $form, $values)
    {
        $username = $values->username;
        $password = $values->password;

        try {
            $this->user->login($username, $password);
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