<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostUpdateFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Translation\t;

class AdminController extends AbstractController
{
    public function __construct(private PostRepository $postRepository)
    {
    }

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
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
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postShow(): Response
    {

        return $this->render('admin/post_show.html.twig', [
            'posts' => $this->postRepository->findAll()
        ]);
    }

    #[Route('/admin/post/delete/{id}', name: 'admin_post_delete')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postDelete(Post $post): Response
    {

        if ($this->postRepository->remove($post, true)) {
            $this->addFlash('success', 'Article supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Erreur lors de la suppression');

        }

        return $this->redirectToRoute("admin_post_show");
    }

    #[Route('/admin/post/update/{id}', name: 'admin_post_update')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postUpdate(Post $post, EntityManagerInterface $entityManager): Response
    {
        $updatePostForm = $this->createForm(PostUpdateFormType::class, $post);
        dump($updatePostForm);
        if ($updatePostForm->isSubmitted()) {
            dd(($this->postRepository->save($post, true)));
            if ($this->postRepository->save($post, true)) {
                $this->addFlash('success', 'Article édité avec succès');
            } else {
                $this->addFlash('danger', 'Erreur lors de l\'édition');
            }

            return $this->redirectToRoute("admin_post_show");
        }

        return $this->render('admin/post_update.html.twig', [
            'updatePostForm' => $updatePostForm
        ]);
    }
}