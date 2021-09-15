<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController{

    /**
     * @Route("/user/dashboard/{idUser}", name="dashboard_user")
     */
    public function dashboard($idUser,ArticleRepository $articleRepository){

        $articles = $articleRepository->findBy(['User']);

        return $this->render('user/dashboard.html.twig', [
            'articles' => $articles
        ]);

    }
    /**
     * @Route("/user/dashboard/{idUser}/create", name="article_create")
     */
    public function createArticle($idUser,ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render('user/dashboard.html.twig', [
            'articles' => $articles
        ]);

    }
    /**
     * @Route("/user/dashboard/{idUser}/update/{idArticle}", name="article_modify")
     */
    public function updateArticle($idUser,$idArticle, ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render('user/dashboard.html.twig', [
            'articles' => $articles
        ]);

    }
    /**
     * @Route("/user/dashboard/{idUser}/delete/{idArticle}", name="article_delete")
     */
    public function deleteArticle($idUser,$idArticle,ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render('user/dashboard.html.twig', [
            'articles' => $articles
        ]);

    }
}