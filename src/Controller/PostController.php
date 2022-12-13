<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'post')]
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $postId = $request->query->get('postId');

        $post = $postRepository->find($postId);

        return $this->render('post/index.html.twig', [
            'post' => $post,
        ]);
    }
}
