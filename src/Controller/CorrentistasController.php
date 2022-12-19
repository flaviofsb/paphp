<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CorrentistasController extends AbstractController
{
    #[Route('/correntistas', name: 'app_correntistas')]
    public function index(): Response
    {
        return $this->render('correntistas/correntistas.html.twig', [
            'controller_name' => 'CorrentistasController',
        ]);
    }

    #[Route('/correntistas/cadastrar', name: 'app_correntistas_cadastrar')]
    public function exibirCadastrar(): Response
    {
        return $this->render('correntistas/cadastrar/index.html.twig', [
            'controller_name' => 'CorrentistasController',
        ]);
    }
}

/**
 * ADICIONAR TRANSACAO
 * EDITAR TRANSACAO
 * LISTAR TRANSACAO
 * 
 * LISTAR CONTAS
 * 
 */
