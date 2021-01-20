<?php


namespace App\Model\Posts;

use Nette;

class Posts
{

    private $database;

    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
    }

    public function getAllPosts()
    {
        return $this->database->query('SELECT * FROM posts')->fetchAll();
    }

    public function getPost($id)
    {
        return $this->database->query('SELECT * FROM posts WHERE id = ?',$id)->fetch();
    }

    public function insertPost($post)
    {
        $this->database->query('INSERT INTO posts',$post);
    }

    public function deletePost($id)
    {
        $this->database->query('DELETE FROM posts WHERE id = ?',$id);
        $this->database->query('DELETE FROM comments WHERE post_id = ?',$id);
    }

    public function insertComment($comment)
    {
        $this->database->query('INSERT INTO comments',$comment);
    }

    public function getAllComments($postId)
    {
        return $this->database->query('SELECT * FROM comments WHERE post_id = ?',$postId);
    }

    public function deleteComment($id)
    {
        $this->database->query('DELETE FROM comments WHERE id = ?',$id);
    }

}