<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
#[Route('user/dashboard/delete-comment/{idComment}',name:'comment_delete')]
    public function deleteComment(
        $idComment,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse {

        $comment = $commentRepository->find($idComment);
        $article = $comment->getArticle();

        if ($this->getUser() == $article->getUser()) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('visitor_article_id', ['id' => $article->getId()]);
    }
}
