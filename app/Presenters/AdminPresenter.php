<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\User\User;

class AdminPresenter extends BasePresenter{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function renderDefault()
    {
        $this->template->users = $this->user->getAllUsers();
    }

    public function handleDeleteUser($id)
    {
        $this->user->deleteUser($id);
        $this->flashMessage('Uživatel byl úspěšně odebrán');
        $this->redirect('Admin:default');
    }
}