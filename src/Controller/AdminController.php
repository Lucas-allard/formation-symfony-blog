<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// #[isGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: 'Accès refuser aux nom-admins')]
    public function index(): Response
    {

//         if(!$this->isGranted('ROLE_ADMIN')) {
//             $this->addFlash('danger', 'Vous devez être admin pour accéder à cette page');
//
//             return $this->redirectToRoute('home');
//         }

        return $this->render('admin/index.html.twig',);
    }

    #[Route('/admin/post/show', name: 'admin_post_show')]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: 'Accès refuser aux nom-admins')]
    public function postShow(PostRepository $postRepository): Response
    {

        return $this->render('admin/post_show.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }
}