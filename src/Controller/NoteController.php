<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Service\NoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    #[Route('visitor/article/{id}/setNote',name:'set_note')]
    public function note(Request $request, int $id, ArticleRepository $articleRepository, NoteService $noteService): RedirectResponse
    {
        $article = $articleRepository->find($id);
        /** @var User $user */
        $user = $this->getUser();
        $note = $request->request->get('flexRadioDefault');
        if ($user) {
            $noteService->setNote($note, $article, $user);
        } else {
            $noteService->setNote($note, $article);
        }
        return $this->redirectToRoute('visitor_article_id', ['id' => $article->getId()]);
    }
}