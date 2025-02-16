<?php

namespace App\View\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{//TODO create main page
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/contacts', name: 'contacts')]
    public function contacts(): Response
    {
        return $this->render('info/contacts.html.twig');
    }

    #[Route('/about-us', name: 'about_us')]
    public function aboutUs(): Response
    {
        return $this->render('info/about_us.html.twig');
    }
}
