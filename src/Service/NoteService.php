<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NoteService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setNote(int $note, Article $article, User $user = null)
    {
        $rate = new Note();
        $rate->setNote($note);
        $rate->setArticle($article);
        $rate->setUser($user);
        $this->entityManager->persist($rate);
        $this->entityManager->flush();
    }
}