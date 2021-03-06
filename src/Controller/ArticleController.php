<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Service\CommentService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class ArticleController extends AbstractController
{

    ///////////////////////////USER/DASHBOARD//////////////////////////////////

    #[Route('/user/dashboard', name: 'dashboard_user')]
    public function dashboard(ArticleRepository $articleRepository, UserRepository $userRepository): Response

    {
        $user = $userRepository->find($this->getUser()->getId());

        $articles = $articleRepository->findBy(['user' => $user]);

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'articles' => $articles
        ]);
    }

    #[Route('/user/dashboard/article/{idArticle}', name: 'article_create_modify')]
    public function updateArticle(


        $idArticle = 0,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): RedirectResponse|Response {
        $article = $idArticle == 0 ?  new Article() : $articleRepository->find($idArticle);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($article->getUser() == null) {
                $article->setUser($this->getUser());
            }
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_user');
        }

        return $this->render('user/formArticle.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/dashboard/delete/{idArticle}', name: 'article_delete')]
    public function deleteArticle(
        $idArticle,
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository
    ): RedirectResponse {

        $article = $articleRepository->find($idArticle);

        if ($this->getUser() == $article->getUser()) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_user');
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route ("/", name="list_articles")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function listArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        return $this->render('visitor/listArticles.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route ("/visitor/article/{id}", name="visitor_article_id")
     * @param ArticleRepository $articleRepository
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param NoteRepository $noteRepository
     * @return Response
     */
    public function showArticle(ArticleRepository $articleRepository, int $id, Request $request, EntityManagerInterface $entityManager, CommentService $commentService, NoteRepository $noteRepository): Response
    {
        $article = $articleRepository->find($id);
        $manager = $article->getUser() == $this->getUser();
        try {
            $rating = $noteRepository->average($article);
        } catch (NoResultException | NonUniqueResultException $e) {
        }

        if ($request->getMethod() == Request::METHOD_POST) {
            $user = null;
            
            if($user = $this->getUser()){
                $commenter = $user->getFirstname() ." ". $user->getLastname();
            } else {
                $commenter = $request->request->get('commenter');
            }
            
            $content = $request->request->get('comment');
            $commentService->newCommentArticle($article, $content, $commenter,$user);
           

            return $this->redirectToRoute('visitor_article_id', ['id' => $id]);
        }
        return $this->render('visitor/article.html.twig', [
            'article' => $article,
            'manager' => $manager,
            'rating' => $rating,
        ]);
    }

    
}
