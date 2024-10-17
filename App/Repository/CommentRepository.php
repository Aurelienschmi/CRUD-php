<?php

namespace App\Repository;

use PDO;
use App\Config\Database;
use App\Entity\Comment;

class CommentRepository
{
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function addComment($userId, $articleId, $content)
    {
        $stmt = $this->db->prepare('INSERT INTO comments (user_id, article_id, content, created_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $articleId, $content, (new \DateTime())->format('Y-m-d H:i:s')]);
    }

    public function getCommentsByArticleId($articleId)
{
    $stmt = $this->db->prepare('SELECT * FROM comments WHERE article_id = ? ORDER BY created_at DESC');
    $stmt->execute([$articleId]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $comments = [];
    foreach ($results as $row) {
        $comment = new Comment(
            $row['user_id'],
            $row['article_id'],
            $row['content'],
            $row['created_at'],
            $row['id']
        );
        $comments[] = $comment;
    }

    return $comments;
}

    public function deleteComment($commentId)
    {
        $stmt = $this->db->prepare('DELETE FROM comments WHERE id = ?');
        $stmt->execute([$commentId]);
    }
}
?>