<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{


    #[Route('/user/dashboard/{idUser}', name: 'dashboard_user')]
    public function dashboard($idUser, ArticleRepository $articleRepository, UserRepository $userRepository)
    {


        $user = $userRepository->find($idUser);

        $articles = $articleRepository->findBy(['user' => $user]);

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'articles' => $articles
        ]);
    }

    #[Route('/user/dashboard/{idUser}/create', name: 'article_create')]
    public function createArticle(
        $idUser,
        ArticleRepository $articleRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ) {
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
    ) {
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
    ) {
        $user = $userRepository->find($idUser);
        $article = $articleRepository->find($idArticle);

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('dashboard_user', ['idUser' => $idUser]);

    }
}
