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
    #[Route('/', name: 'app_home')]
    public function index(HttpClientInterface $articleClient, PaginatorInterface $paginator): Response
    {



        $response = $articleClient->request(Request::METHOD_POST, '/api/article/');
        // dump($response);
        try {
            $articles = $response->toArray();
        } catch (ClientExceptionInterface $th) {
            $error = $th->getMessage();
            exit;
        }

        return $this->render('home/index.html.twig', [
            'articles' => $articles['item'],
            'pagination'      => $articles
            // 'error'     => $error
        ]);
    }


    #[Route('/article/{id}', name: 'app_article')]
    public function item(Request $request, HttpClientInterface $articleClient, $id): Response
    {
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
