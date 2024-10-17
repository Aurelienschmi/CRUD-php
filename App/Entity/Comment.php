<?php
namespace App\Entity;

class Comment
{
    private $id;
    private $userId;
    private $articleId;
    private $content;
    private $createdAt;
    private $user;

    public function __construct($userId, $articleId, $content, $createdAt = null, $id = null) {
        $this->id = $id;
        $this->userId = $userId;
        $this->articleId = $articleId;
        $this->content = $content;
        $this->createdAt = $createdAt ? new \DateTime($createdAt) : new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getArticleId()
    {
        return $this->articleId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
?>