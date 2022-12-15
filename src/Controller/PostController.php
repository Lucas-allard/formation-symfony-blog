<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    public function __construct(private readonly CategoryRepository $categoryRepository, private readonly PostRepository $postRepository)
    {
    }


    /**
     * @param PostRepository $postRepository
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'posts' => $this->postRepository->findAll(),
        ]);
    }

    /**
     * @param Category $category
     * @return Response
     */
    #[Route('/post/category/{id<[0-9]+>}/', name: 'index_by_category')]
    public function showByCategory(Category $category): Response
    {

        return $this->render('post/showByCategory.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'posts' => $category->getPosts(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */

    #[Route('/post/search', name: 'index_by_search')]
    public function showBySearch(Request $request): Response
    {

        $searchValue = $request->request->get('search');

        return $this->render('post/showByCategory.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
            'posts' => $this->postRepository->findBySearch($searchValue),
        ]);
    }

    /**
     * @param Post $post
     * @return Response
     */
    #[Route('/post/{id<[0-9]+>}', name: 'post')]
    public function show(Post $post): Response
    {

        $comment = new Comment();
        $form = $this->createFormBuilder($comment)
            ->add('body', TextType::class)
            ->add('send', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $post->getComments()
        ]);
    }
}
