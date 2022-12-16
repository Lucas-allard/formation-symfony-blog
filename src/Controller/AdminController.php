<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Translation\t;

class AdminController extends AbstractController
{
    public function __construct(private PostRepository $postRepository, private CommentRepository $commentRepository)
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

        $this->postRepository->remove($post, true);

        return $this->redirectToRoute("admin_post_show");
    }

    #[Route('/admin/post/update/{id}', name: 'admin_post_update')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postUpdate(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $updatePostForm = $this->createForm(PostFormType::class, $post);
        $updatePostForm->handleRequest($request);


        if ($updatePostForm->isSubmitted() && $updatePostForm->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Article édité avec succès');

            return $this->redirectToRoute("admin_post_show");
        }

        return $this->render('admin/post_update.html.twig', [
            'updatePostForm' => $updatePostForm
        ]);
    }

    #[Route('/admin/comment/delete/{id}', name: 'admin_comment_delete')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function commentDelete(Post $post, Comment $comment): Response
    {

        $this->commentRepository->remove($comment, true);

        return $this->redirectToRoute('admin_comment_delete', ["id" => $post->getId()]);
    }

    #[Route('/admin/comment/show/unvalid/{id}', name: 'admin_comment_unvalid')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function showUnvalidComment(Comment $comment): Response
    {
        ;

        return $this->render('admin/comment_unvalid.html.twig', [
            'comments' => $this->commentRepository->findBy(["isValid" => false])
        ]);
    }


    #[Route('/admin/comment/validate/{id}', name: 'admin_comment_validate')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function commentValidate(Post $post, Comment $comment): Response
    {

        $comment->setIsValid(true);

        return $this->redirectToRoute('admin_comment_delete', ["id" => $post->getId()]);
    }

}