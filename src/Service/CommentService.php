<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Comment;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentService {

    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;    
    }

    public function newCommentArticle(Article $article, string $content, string $commenter, UserInterface|null $user)
    {
        $comment = new Comment();
        $comment->setDate(new DateTime());
        $comment->setArticle($article);
        $comment->setContent($content);
        $comment->setCommenter($commenter);
        $comment->setUser($user);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

    }
}