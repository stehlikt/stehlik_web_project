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
        return $this->database->query('SELECT p.id, p.title, p.content, p.created_at, p.user_id , u.id AS users , u.username FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.id = ? ',$id)->fetch();
    }

    public function insertPost($post)
    {
        $this->database->query('INSERT INTO posts',$post);
    }

    public function updatePost($post,$id)
    {
        $this->database->query('UPDATE posts SET title = ? , content = ? WHERE id = ?',$post['title'],$post['content'],$id);
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