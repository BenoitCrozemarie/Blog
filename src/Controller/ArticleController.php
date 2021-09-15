<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController{

    
    #[Route('/user/dashboard/{idUser}', name: 'dashboard_user')]
    public function dashboard($idUser,ArticleRepository $articleRepository,UserRepository $userRepository){

        
        $user = $userRepository->find($idUser);
       
        $articles = $articleRepository->findBy(['user' => $user]);

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'articles' => $articles
        ]);
    }

    #[Route('/user/dashboard/{idUser}/create', name: 'article_create')]
    public function createArticle($idUser,ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render('user/dashboard.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/user/dashboard/{idUser}/update/{idArticle}', name: 'article_modify')]
    public function updateArticle($idUser,$idArticle, ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render('user/dashboard.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/user/dashboard/{idUser}/delete/{idArticle}', name: 'article_delete')]
    public function deleteArticle($idUser,$idArticle,ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render('user/dashboard.html.twig', [
            'articles' => $articles
        ]);
    }
}