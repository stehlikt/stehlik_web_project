<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Posts\Posts;
use Nette\Application\UI\Form;

class PostsPresenter extends BasePresenter{

    private $posts;

    public function __construct(Posts $posts)
    {
        $this->posts = $posts;

    }

    public function renderDefault()
    {
        $this->template->posts = $this->posts->getAllPosts();
    }

    public function renderShow($postId)
    {
        $this->template->post = $this->posts->getPost($postId);
        $this->template->comments = $this->posts->getAllComments($postId);
    }

    public function createComponentAddPostForm(): Form
    {
        $form =new Form ();

        $form->addTextArea('title','Název příspěvku')
            ->setRequired();
        $form->addTextArea('content','Příspěvěk')
            ->setRequired();
        $form->addSubmit('Odeslat');

        $form->onSuccess[] =  [$this, 'addPostFormSucceeded'];

        return $form;

    }

    public function addPostFormSucceeded(Form $form, $values)
    {

        $this->posts->insertPost([
            'title' => $values->title,
            'content' => $values->content,
            'user_id' => '1']);

        $this->flashMessage('Příspěvěk byl úspěšně vytvořen');
        $this->redirect('Posts:default');
    }

    public function handleDeletePost($postId)
    {
        $this->posts->deletePost($postId);
        $this->flashMessage('Příspěvek byl úspěšně smazán');
        $this->redirect('Posts:default');
    }

    protected function createComponentCommentForm(): Form
    {

        $form = new Form();

        $form->addTextArea('name','Jméno:')
            ->setRequired();
        $form->addTextArea('content','Obsah:')
            ->setRequired();
        $form->addSubmit('Odeslat');

        $form->onSuccess[] = [$this, 'commentFormSucceeded'];

        return $form;

    }

    public function commentFormSucceeded(Form $form, $values)
    {
        $postId = $this->getParameter('postId');

        $this->posts->insertComment([
            'name' => $values->name,
            'post_id' => $postId,
            'content' => $values->content,
            'user_id' => 1
        ]);

        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }

    public function handleDeleteComment($commentId)
    {
        $this->posts->deleteComment($commentId);
        $this->flashMessage('Komentář byl úspěšně smazán');
        $this->redirect('Posts:default');

    }

}