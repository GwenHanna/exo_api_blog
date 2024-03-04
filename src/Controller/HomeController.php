<?php

namespace App\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    // Création de la route pour afficher tous les articles
    #[Route('/', name: 'app_home')]
    public function index(HttpClientInterface $articleClient, PaginatorInterface $paginator): Response
    {
        // Effectue une requête HTTP GET vers l'API pour récupérer la liste des articles
        $response = $articleClient->request(Request::METHOD_GET, '/api/article/');
        // dump($response);
        try {
            // Transforme la réponse en tableau associatif contenant les articles
            $articles = $response->toArray();
        } catch (ClientExceptionInterface $th) {
            // En cas d'erreur, capture l'exception et stocke le message d'erreur
            $error = $th->getMessage();
            exit;
        }
         // Rend la vue 'home/index.html.twig' en passant la liste des articles
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Création de la route pour afficher les détails d'un article
    #[Route('/article/{id}', name: 'app_article')]
    public function item(Request $request, HttpClientInterface $articleClient, $id): Response
    {
        // Effectue une requête HTTP GET vers l'API pour récupérer les détails d'un article spécifique
        $req = $articleClient->request(Request::METHOD_GET, '/api/article/{id}', [
            'json'  => ['id' => $id]
        ]);
        try {
            $article = $req->toArray();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }

        return $this->render('article/article.html.twig', [
            'article'   => $article
        ]);
    }
}
