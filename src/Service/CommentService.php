<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CommentService {

    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;    
    }

    public function newCommentArticle(Article $article, string $content, string $commenter)
    {
        $comment = new Comment();
        $comment->setDate(new DateTime());
        $comment->setArticle($article);
        $comment->setContent($content);
        $comment->setCommenter($commenter);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

    }
}