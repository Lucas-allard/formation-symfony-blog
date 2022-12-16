<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Translation\t;

class AdminController extends AbstractController
{
    private Request $request;

    public function __construct(private PostRepository $postRepository, private CommentRepository $commentRepository)
    {
        $this->request = new Request();
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

        return $this->render('admin/post_admin.html.twig', [
            'posts' => $this->postRepository->findAll()
        ]);
    }

    #[Route('/admin/post/validate', name: 'admin_post_validate')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postValidate(Post $post): Response
    {
        $token = $this->request->query->get("token");

        if ($this->isCsrfTokenValid('post' . $post->getId(), $token)) {

            $post->setIsPublished(true);

            $this->commentRepository->save($post, true);
        }

        return $this->render('admin/post_admin.html.twig', [
            'posts' => $this->postRepository->findAll()
        ]);
    }

    #[Route('/admin/post/delete/{id}', name: 'admin_post_delete')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postDelete(Post $post): Response
    {

        $token = $this->request->query->get("token");

        if ($this->isCsrfTokenValid('post' . $post->getId(), $token)) {
            $this->postRepository->remove($post, true);
        }


        return $this->redirectToRoute("admin_post_show");
    }

    #[Route('/admin/post/update/{id}', name: 'admin_post_update')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postUpdate(Post $post, EntityManagerInterface $entityManager): Response
    {
        $updatePostForm = $this->createForm(PostFormType::class, $post);
        $updatePostForm->handleRequest($this->request);


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

    #[Route('/admin/comment/show/unvalid', name: 'admin_comment_unvalid')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function showUnvalidComment(): Response
    {
        $comments = $this->commentRepository->findBy(["isValid" => false]);

        return $this->render('admin/comment_admin.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('/admin/comment/show/valid', name: 'admin_comment_valid')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function showValidComment(): Response
    {
        $comments = $this->commentRepository->findBy(["isValid" => true]);

        return $this->render('admin/comment_admin.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('/admin/comment/delete/{id}', name: 'admin_comment_delete')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function commentDelete(Comment $comment): Response
    {
        $token = $this->request->query->get("token");

        if ($this->isCsrfTokenValid('comment' . $comment->getId(), $token)) {
            $this->commentRepository->remove($comment, true);

        }
        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/comment/validate/{id}', name: 'admin_comment_validate')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function commentValidate(Comment $comment): Response
    {
        $token = $this->request->query->get("token");

        if ($this->isCsrfTokenValid('comment' . $comment->getId(), $token)) {

            $comment->setIsValid(true);

            $this->commentRepository->save($comment, true);
        }

        return $this->redirectToRoute('admin_comment_unvalid');
    }

}