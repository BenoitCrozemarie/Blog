<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @param $idUser
     * @param ArticleRepository $articleRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/user/dashboard/{idUser}', name: 'dashboard_user')]
    public function dashboard($idUser, ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($idUser);

        $articles = $articleRepository->findBy(['user' => $user]);

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'articles' => $articles
        ]);
    }

    /**
     * @param $idUser
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route('/user/dashboard/{idUser}/create', name: 'article_create')]
    public function createArticle(
        $idUser,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): RedirectResponse|Response
    {
        $user = $userRepository->find($idUser);
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd('submit');
            $article->setUser($user);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_user', ['idUser' => $idUser]);
        }

        return $this->render('user/formArticle.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/dashboard/{idUser}/update/{idArticle}', name: 'article_modify')]
    public function updateArticle(
        $idUser,
        $idArticle,
        ArticleRepository $articleRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): RedirectResponse|Response
    {
        $user = $userRepository->find($idUser);
        $article = $articleRepository->find($idArticle);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd('submit');
            //$article->setUser($user);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_user', ['idUser' => $idUser]);
        }

        return $this->render('user/formArticle.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/dashboard/{idUser}/delete/{idArticle}', name: 'article_delete')]
    public function deleteArticle(
        $idUser,
        $idArticle,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository
    ): RedirectResponse
    {
        $user = $userRepository->find($idUser);
        $article = $articleRepository->find($idArticle);

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('dashboard_user', ['idUser' => $idUser]);
    }

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
     * @return Response
     */
    public function showArticle(ArticleRepository $articleRepository, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = $articleRepository->find($id);

        if ($request->getMethod() == Request::METHOD_POST) {
            $content = $request->request->get('comment');
            $commenter = $request->request->get('commenter');
            $comment = $this->commentArticle($article, $content, $commenter);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('visitor_article_id', ['id' => $id]);

        }
        return $this->render('visitor/article.html.twig', [
            'article' => $article,
        ]);
    }

    public
    function commentArticle(Article $article, string $content, string $commenter): Comment
    {
        $comment = new Comment();
        $comment->setDate(new DateTime());
        $comment->setArticle($article);
        $comment->setContent($content);
        $comment->setCommenter($commenter);

        return $comment;
    }
}

