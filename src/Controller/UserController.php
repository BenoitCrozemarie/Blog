<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/article/liste", name="list_article")
     */
    public function dashboard(ArticleRepository $articleRepository)
    {
        return $this->render('article/liste.html.twig');
    }


    /**
     * @Route("/register", name="user_register")
     * @return Response
     */
    public function register(Request $request)
    {
        $registerForm = $this->createForm(RegisterType::class);
        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            // le mot de passe est automatiquement hashé
            return $this->redirectToRoute('list_article');
        }
        return $this->render('user/register.html.twig', [
            'registerForm' => $registerForm->createView()
        ]);
    }
}
