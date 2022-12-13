<?php

namespace App\Controller;

use App\Entity\Category;
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

    /**
     * @param CategoryRepository $categoryRepository
     * @param Category $category
     * @return Response
     */
    #[Route('/post/category/{id<[0-9]+>}/', name: 'index_by_category')]
    public function showByCategory(CategoryRepository $categoryRepository,Category $category): Response
    {

        return $this->render('post/showByCategory.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'posts' => $category->getPosts(),
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
