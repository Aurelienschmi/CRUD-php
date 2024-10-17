<?php
    namespace App\Entity;

    class Article {
        private $id;
        private $userId;
        private $title;
        private $content;
        private $image;
        private $authorName;
        private $likes;
    
        public function __construct($userId, $title, $content, $image, $id = null, $authorName = null, $likes =  0) {
            $this->userId = $userId;
            $this->title = $title;
            $this->content = $content;
            $this->image = $image;
            $this->id = $id;
            $this->authorName = $authorName;
            $this->likes = $likes;
        }
    
        public function getId() { return $this->id; }
        public function getUserId() { return $this->userId; }
        public function getTitle() { return $this->title; }
        public function getContent() { return $this->content; }
        public function getImage() { return $this->image; }
        public function getAuthorName() {return $this->authorName; }
        public function getLikes() { return $this->likes; }
    }
    