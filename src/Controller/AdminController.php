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

class AdminController extends AbstractController
{

    public function __construct(
        private PostRepository    $postRepository,
        private CommentRepository $commentRepository
    )
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

        return $this->render('admin/post_admin.html.twig', [
            'posts' => $this->postRepository->findAll()
        ]);
    }

    #[Route('/admin/post/validate/{id<[0-9]+>}', name: 'admin_post_validate')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postValidate(Request $request, Post $post): Response
    {
        $token = $request->query->get("token");

        if ($this->isCsrfTokenValid('post' . $post->getId(), $token)) {

            $post->setIsPublished(!$post->isIsPublished());

            $this->postRepository->save($post, true);

            $this->addFlash('success', 'Publication de l\'article edité avec succès');

        }

        return $this->render('admin/post_admin.html.twig', [
            'posts' => $this->postRepository->findAll()
        ]);
    }

    #[Route('/admin/post/add/', name: 'admin_post_add')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postAdd(Request $request): Response
    {
        $post = new Post();

        $postForm = $this->createForm(PostFormType::class, $post);
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post->setUser($this->getUser());

            $this->postRepository->save($post, true);

            $this->addFlash('success', 'Article ajouté avec succès');

            return $this->redirectToRoute("admin_post_show");
        }

        return $this->render('admin/post_update.html.twig', [
            'postForm' => $postForm
        ]);
    }

    #[Route('/admin/post/update/{id<[0-9]+>}', name: 'admin_post_update')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postUpdate(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
            $postForm = $this->createForm(PostFormType::class, $post);
            $postForm->handleRequest($request);

            if ($postForm->isSubmitted() && $postForm->isValid()) {
                $entityManager->persist($post);
                $entityManager->flush();

                $this->addFlash('success', 'Article édité avec succès');

                return $this->redirectToRoute("admin_post_show");
            }

        return $this->render('admin/post_update.html.twig', [
            'postForm' => $postForm
        ]);
    }

    #[Route('/admin/post/delete/{id<[0-9]+>}', name: 'admin_post_delete')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function postDelete(Request $request, Post $post): Response
    {
        $token = $request->query->get("token");

        if ($this->isCsrfTokenValid('post' . $post->getId(), $token)) {
            $this->postRepository->remove($post, true);

            $this->addFlash('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute("admin_post_show");
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

    #[Route('/admin/comment/delete/{id<[0-9]+>}', name: 'admin_comment_delete')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function commentDelete(Request $request, Comment $comment): Response
    {
        $token = $request->query->get("token");

        if ($this->isCsrfTokenValid('comment' . $comment->getId(), $token)) {
            $this->commentRepository->remove($comment, true);

            $this->addFlash('success', 'Commentaire supprimé avec succès');

        }
        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/comment/validate/{id<[0-9]+>}', name: 'admin_comment_validate')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refuser aux nom-admins', statusCode: 403)]
    public function commentValidate(Request $request, Comment $comment): Response
    {
        $token = $request->query->get("token");

        if ($this->isCsrfTokenValid('comment' . $comment->getId(), $token)) {

            $comment->setIsValid(true);

            $this->commentRepository->save($comment, true);

            $this->addFlash('success', 'Commentaire validé avec succès');

        }

        return $this->redirectToRoute('admin_comment_unvalid');
    }

}