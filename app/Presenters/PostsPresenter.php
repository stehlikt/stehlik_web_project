<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\Posts\Posts;
use Nette\Application\UI\Form;

class PostsPresenter extends BasePresenter
{

    private $posts;

    public function __construct(Posts $posts)
    {
        $this->posts = $posts;
    }

    public function renderDefault($page = 1)
    {
        $postCount = $this->posts->getPostsCount();


        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($postCount);
        $paginator->setItemsPerPage(2);
        $paginator->setPage($page);

        $posts = $this->posts->getAllPosts($paginator->getLength(), $paginator->getOffset());

        $this->template->posts = $posts;

        $this->template->paginator = $paginator;
    }

    public function renderShow($postId)
    {
        $this->template->post = $this->posts->getPost($postId);
        $this->template->comments = $this->posts->getAllComments($postId);
    }

    public function createComponentAddPostForm(): Form
    {
        $form = new Form ();

        $form->addTextArea('title', 'Název příspěvku: ')
            ->setRequired('Zadejte prosím název příspěvku');
        $form->addTextArea('content', 'Příspěvěk: ')
            ->setRequired('Vyplňte příspěvek');
        $form->addSubmit('Odeslat');

        $form->onSuccess[] = [$this, 'addPostFormSucceeded'];

        return $form;

    }

    public function addPostFormSucceeded(Form $form, $values)
    {
        $postId = $this->getParameter('postId');

        if ($postId) {
            $this->posts->updatePost($values, $postId);

            $this->flashMessage('Příspěvěk byl úspěšně editovan');
            $this->redirect('Posts:default');
        } else {
            $this->posts->insertPost([
                'title' => $values->title,
                'content' => $values->content,
                'user_id' => $this->user->getId()
            ]);

            $this->flashMessage('Příspěvěk byl úspěšně vytvořen');
            $this->redirect('Posts:default');
        }
    }

    public function actionEdit($postId)
    {
        $post = $this->posts->getPost($postId);
        $this['addPostForm']->setDefaults($post);
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

        $form->addTextArea('content', 'Obsah:')
            ->setRequired();
        $form->addSubmit('send', 'Přidat komentář');

        $form->onSuccess[] = [$this, 'commentFormSucceeded'];

        return $form;
    }

    public function commentFormSucceeded(Form $form, $values)
    {
        $postId = $this->getParameter('postId');

        $this->posts->insertComment([
            'post_id' => $postId,
            'content' => $values->content,
            'user_id' => $this->user->getId()
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