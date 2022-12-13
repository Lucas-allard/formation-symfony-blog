<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $name = $request->request->get("firstName");

        $email = $request->request->get("email");

        $password = $request->request->get("password");

        return $this->render('home/index.html.twig', [
            'name' => $name,
            'email' => $email,
            "password" => $password
        ]);
    }
}
