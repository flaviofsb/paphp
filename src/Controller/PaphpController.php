<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaphpController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('home/home.html.twig', [ 
            'controller_name' => 'PaphpController',
        ]);
    }
    #[Route('/login', name: 'app_login')]
    public function indexLogin(): Response
    {
        return $this->render('form/login.html.twig', [ 
            'controller_name' => 'PaphpController',
        ]);
    }

    #[Route('/retorno_login', name: 'app_retorno_login')]
    public function retornoLogin(): Response
    {
        return $this->render('home/home.html.twig', [ 
            'controller_name' => 'PaphpController',
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {}
}
