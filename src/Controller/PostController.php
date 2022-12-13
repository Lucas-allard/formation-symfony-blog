<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/post/{id}', name: 'post')]
    public function show(Post $post): Response
    {

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
