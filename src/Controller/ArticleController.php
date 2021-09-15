<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route ("/visitor/articles", name="list_articles")
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