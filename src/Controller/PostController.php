<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @param CategoryRepository $categoryRepository
     * @param PostRepository $postRepository
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(CategoryRepository $categoryRepository, PostRepository $postRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/post/{category}', name: 'home')]
    public function showByCategory(CategoryRepository $categoryRepository, PostRepository $postRepository): Response
    {

//        return $this->render('home/index.html.twig', [
//            'categories' => $categoryRepository->findAll(),
//            'posts' => $postRepository->findAll(),
//        ]);
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
