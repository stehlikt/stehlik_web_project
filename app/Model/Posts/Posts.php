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

    public function getAllPosts($limit, $offset)
    {
        return $this->database->query('SELECT p.id, p.title, p.content, p.created_at, p.user_id , u.id AS users , u.username FROM  posts p INNER JOIN users u ON p.user_id = u.id ORDER BY p.id DESC LIMIT ?  OFFSET ?', $limit, $offset)->fetchAll();
    }

    public function getPost($id)
    {
        return $this->database->query('SELECT p.id, p.title, p.content, p.created_at, p.user_id , u.id AS users , u.username FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.id = ? ', $id)->fetch();
    }

    public function getPostsCount()
    {
        return $this->database->query('SELECT COUNT(*) FROM posts')->fetchField();
    }

    public function insertPost($post)
    {
        $this->database->query('INSERT INTO posts', $post);
    }

    public function updatePost($post, $id)
    {
        $this->database->query('UPDATE posts SET title = ? , content = ? WHERE id = ?', $post['title'], $post['content'], $id);
    }

    public function deletePost($id)
    {
        $this->database->query('DELETE FROM posts WHERE id = ?', $id);
        $this->database->query('DELETE FROM comments WHERE post_id = ?', $id);
    }

    public function insertComment($comment)
    {
        $this->database->query('INSERT INTO comments', $comment);
    }

    public function getAllComments($postId)
    {
        return $this->database->query('SELECT c.id, c.post_id, c.user_id, c.content, c.created_at, u.id AS users, u.username FROM comments c INNER JOIN users u ON c.user_id = u.id  WHERE post_id = ?', $postId);
    }

    public function deleteComment($id)
    {
        $this->database->query('DELETE FROM comments WHERE id = ?', $id);
    }

}