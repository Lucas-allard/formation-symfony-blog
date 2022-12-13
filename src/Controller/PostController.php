<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @param PostRepository $postRepository
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }

    /**
     * @param Post $post
     * @return Response
     */
    #[Route('/post/{id<[0-9]+>}', name: 'post')]
    public function show(Post $post): Response
    {

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
