<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post', methods: ["GET"])]
    public function index(HttpClientInterface $client): Response
    {
        $response = $client->request(
            'GET',
            'https://freefakeapi.io/api/posts'
        );
        $statusCode = $response->getStatusCode();
        $posts = $response->toArray();
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }


    #[Route('/post_with_token', name: 'app_post_with_token', methods: ["GET"])]
    public function indexWithToken(HttpClientInterface $client): Response
    {
        $response = $client->request('GET', 'https://freefakeapi.io/authapi/posts', [
            // use a different HTTP Basic authentication only for this request
            'auth_bearer' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJlbWFpbCI6Im1pa2UucGF5bmVAZXhhbXBsZS5jb20iLCJpYXQiOjE3MDA1Njg2OTEsImV4cCI6MTcwMDU3MjI5MX0.GnMxBFTIkQWWOj6m4mkGOsYmXQaHt7BckqYmbf07nUI',
            // 'auth_bearer' => 'true', Pour le faire via le fichier de configuration (fonctionne ap)

        ]);
        $posts =  $response->toArray();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/traitement_long', name: 'app_traitement_long', methods: ["GET"])]
    public function tester_simuler_traitement_long(CacheInterface $cache)
    {
        // mise en cache du texte 
        $mytexte = $cache->get('traitement long', function () {
            return $this->simuler_traitement_long();
        });
        return $this->render('post/long.html.twig', [
            'texte' => $mytexte,
        ]);
    }
    public function simuler_traitement_long()
    {
        sleep(4);
        return "traitement long";
    }

    #[Route('/post_with_cache', name: 'app_post_with_cache', methods: ["GET"])]
    public function index_with_cache(HttpClientInterface $client, CacheInterface $cache): Response
    {
        $posts = $cache->get('posts', function (ItemInterface $item) use ($client) {
            $response = $client->request(
                'GET',
                'https://freefakeapi.io/api/posts'
            );
            $posts = $response->toArray();
            $item->expiresAfter(20);

            return $posts;
        });

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
