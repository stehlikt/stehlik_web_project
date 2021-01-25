<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use Nette\Application\UI;

abstract class BasePresenter extends UI\Presenter{

    public function startup()
    {
        parent::startup();

        if($this->user->isLoggedIn() && $this->user->isInRole('admin'))
        {
            $this->setLayout('admin');
        }
        else
        {
            $this->redirect(':Homepage:default');
        }

    }

}