<?php

namespace App\Controller;

use App\Entity\Transacoes;
use App\Repository\ContasRepository;
use Symfony\Component\Form\FormError;
use App\Repository\AgenciasRepository;
use App\Repository\TransacoesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TransacoesDepositarFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TransacoesDepositarPublicoFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaphpController extends AbstractController
{
    #[Route('/transacoes/depositar', name: 'app_depositar_padrao')]
    public function depositarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
    {

        $deposito = new Transacoes();
        $deposito->setTipo("Depósito");
        $form = $this->createForm(TransacoesDepositarPublicoFormType::class, $deposito);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            //$userLogado = $this->getUser();

            $data = $form->getData();
            $contaDestinoId = str_replace("_", "", $data->getContaDestino());
            $valor = $data->getValor();
            //dump($contaDestinoId);
            // Busca a conta com o id informado pelo usuário
            $contaDestino = $contas->findOneBy(['id' => $contaDestinoId]);

            if (!$contaDestino) {
                // Adiciona uma mensagem de erro ao formulário caso a conta não seja encontrada
                $form->get('conta_destino')->addError(new FormError('Conta para depósito inválida'));

                return $this->render('transacoes/depositar.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Cria um novo objeto Transacoes

            $deposito->setContaDestino($contaDestinoId);
           // $deposito->setUser($userLogado);
            $deposito->setDataHora(new \DateTime());
            $deposito->setValor($valor);

            // Persiste o objeto e faz o flush
            $entityManager->persist($deposito);
            $entityManager->flush();

            $contaDestino->setSaldo($valor + $contaDestino->getSaldo());
            $contas->save($contaDestino, true);
            $this->addFlash('notice', 'Depósito cadastrado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('transacoes/depositar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/', name: 'app_index')]
    public function index(AgenciasRepository $agencias): Response
    {
        $agencias = $agencias->findAll();
        return $this->render('home/home.html.twig', [ 
            'controller_name' => 'PaphpController',
            'agencias' => $agencias,
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
