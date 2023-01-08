<?php

namespace App\Controller;

use App\Entity\Contas;
use App\Entity\Transacoes;
use App\Form\ContasFormType;
use App\Repository\ContasRepository;
use App\Form\TransacoesSacarFormType;
use Symfony\Component\Form\FormError;
use App\Repository\TransacoesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TransacoesDepositarFormType;
use App\Form\TransacoesTransferirFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CorrentistasController extends AbstractController

{
    private $contas;

    #[Route('/correntistas/contas/cancelar/{conta}', name: 'app_correntistas_cancelar_contas')]
    public function aprovarContas(Contas $conta, Request $request, ContasRepository $contas): Response
    {

        $userAutenticado = $this->getUser();
        if ($conta) {

            $contaObj = $contas->findOneBy(['id' => $conta->getId()]);
            
            $contaObj->setDataHoraCancelamento(new \DateTime());
            $contaObj->setStatus(2);
            //dd($conta);
            //$agencia = $form->getData();
            // salvar e dar flush

            //dd($agencia);
            // do anything else you need here, like send an email
            $contas->save($contaObj, true);
            $this->addFlash('notice', 'Conta cancelada com sucesso.');
            return $this->redirectToRoute('app_index');
        }
    }

    #[Route('/correntistas/contas', name: 'app_correntistas_contas_listar')]
    public function listarContas(ContasRepository $contas): Response
    {
        $userAutenticado = $this->getUser();

        //dd($userAutenticado);
        $contas = $contas->findBy(['correntista' => $userAutenticado->getId()]);
        //dd($contas);
        return $this->render('correntistas/contas/listar.html.twig', [
            'controller_name' => 'CorrentistasController',
            'contas' => $contas,
        ]);
    }

    #[Route('/correntistas/contas/cadastrar', name: 'app_correntista_cadastrar_contas')]
    public function cadastrarContas(Request $request, ContasRepository $contas, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContasFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $userLogado = $this->getUser();
            $conta = $form->getData();
            $conta->setCorrentista($userLogado);
            $conta->setDataHoraCriacao(new \DateTime());

            //$contasAnteriores = $contas->findOneBy(['correntista' => $userLogado->getId(), 'data_hora_aprovacao' => array('$ne' => null)]);



            $conn = $entityManager->getConnection();
            $sql = 'SELECT * FROM contas WHERE data_hora_aprovacao IS NOT NULL AND correntista_id = :correntista';
            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery(['correntista' => $userLogado->getId()]);
            //$result->fetchAllAssociative();

            $contasAnteriores = $result->fetchAllAssociative();


            if ($contasAnteriores) { // se ja existe alguma conta deste correntista aprovada
                $contasObj = $contas->findOneBy(['id' => $contasAnteriores[0]['id']]);
                $conta->setStatus(1);
                $conta->setDataHoraAprovacao(new \DateTime());
                $conta->setGerenteAprovacao($contasObj->getGerenteAprovacao());
            }


            // salvar e dar flush
            $contas->save($conta, true);

            $this->addFlash('notice', 'Conta cadastrada com sucesso.');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('correntistas/contas/cadastrar.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    #[Route('/correntistas/transacoes/exibir/{transacao}', name: 'app_correntistas_exibir')]
    public function exibir(Transacoes $transacao): Response
    {
        // dd($postID);

        return $this->render('correntistas/transacoes/exibir.html.twig', [
            'transacao' => $transacao
        ]);
    }

    #[Route('/correntistas/transacoes', name: 'app_correntistas_transacoes_listar')]
    public function listarTransacoes(TransacoesRepository $transacoes): Response
    {
        // dd($postID);

        $userAutenticado = $this->getUser();
        $transacoes = $transacoes->findBy(['user' => $userAutenticado->getId()]);
        //dd($transacoes);
        return $this->render('correntistas/transacoes/listar.html.twig', [
            'transacoes' => $transacoes
        ]);
    }

    #[Route('/correntistas/transacoes/cadastrar', name: 'app_correntista_cadastrar_transacao')]
    public function exibirCadastro(Request $request): Response
    {
        return $this->render('correntistas/transacoes/cadastrar.html.twig', [
            'controller_name' => 'CorrentistasController',
        ]);
    }

    #[Route('/correntistas/transacoes/depositar', name: 'app_correntista_depositar_transacao')]
    public function depositarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
    {

        $deposito = new Transacoes();
        $deposito->setTipo("Depósito");
        $form = $this->createForm(TransacoesDepositarFormType::class, $deposito);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $userLogado = $this->getUser();

            $data = $form->getData();
            $contaDestinoId = str_replace("_", "", $data->getContaDestino());
            $valor = $data->getValor();
            //dump($contaDestinoId);
            // Busca a conta com o id informado pelo usuário
            $contaDestino = $contas->findOneBy(['id' => $contaDestinoId, 'status'=> 1]);

            if (!$contaDestino) {
                // Adiciona uma mensagem de erro ao formulário caso a conta não seja encontrada
                $form->get('conta_destino')->addError(new FormError('Conta para depósito inválida'));

                return $this->render('correntistas/transacoes/depositar.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Cria um novo objeto Transacoes

            $deposito->setContaDestino($contaDestinoId);
            $deposito->setUser($userLogado);
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

        return $this->render('correntistas/transacoes/depositar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/correntistas/transacoes/sacar', name: 'app_correntista_sacar_transacao')]
    public function sacarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
    {

        $saque = new Transacoes();
        $saque->setTipo("Saque");
        $form = $this->createForm(TransacoesSacarFormType::class, $saque);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $userLogado = $this->getUser();

            $data = $form->getData();
            
            $contaDestinoId = str_replace("_", "", $data->getContaDestino());
            $valor = $data->getValor();
            //dump($contaDestinoId);
            // Busca a conta com o id informado pelo usuário
            $contaDestino = $contas->findOneBy(['id' => $contaDestinoId, 'status'=> 1]);

            // verifica se tem saldo

            $saldoAposOperacaoMaiorQueZero = (($contaDestino->getSaldo() - $valor) >= 0);
            //dd($contaDestino->getSaldo() - $valor);
            if (!$saldoAposOperacaoMaiorQueZero) {
                // Adiciona uma mensagem de erro ao formulário caso a o saldo vá ficar negativo
                $form->get('valor')->addError(new FormError('Valor indisponível no momento. Seu saldo é de: ' . $contaDestino->getSaldo()));

                return $this->render('correntistas/transacoes/sacar.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            // Cria um novo objeto Transacoes

            $saque->setcontaDestino($contaDestino->getId());
            $saque->setUser($userLogado);
            $saque->setDataHora(new \DateTime());
            $saque->setValor($valor);

            // Persiste o objeto e faz o flush
            $entityManager->persist($saque);
            $entityManager->flush();

            $contaDestino->setSaldo($contaDestino->getSaldo() - $valor);
            $contas->save($contaDestino, true);
            $this->addFlash('notice', 'Saque realizado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('correntistas/transacoes/sacar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/correntistas/transacoes/transferir', name: 'app_correntista_transferir_transacao')]
    public function transferirTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
    {

        $transferencia = new Transacoes();
        $transferencia->setTipo("Transferência");
        $form = $this->createForm(TransacoesTransferirFormType::class, $transferencia);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $userLogado = $this->getUser();

            $data = $form->getData();
            
            $contaDestinoId = str_replace("_", "", $data->getContaDestino());
            
            $valor = $data->getValor();
            $contaOrigemId = $data->getContaOrigem();
            
            
            //dump($contaDestinoId);
            // Busca a conta com o id informado pelo usuário
            $contaDestino = $contas->findOneBy(['id' => $contaDestinoId, 'status'=> 1]);
            $contaOrigem = $contas->findOneBy(['id' => $contaOrigemId, 'status'=> 1]);
            // verifica se a conta destino existe
            //dump($contaDestino);
            //dd($contaOrigem);
            if (!$contaDestino) {          

                $form->get('conta_destino')->addError(new FormError('Conta para transferência inválida'));

                return $this->render('correntistas/transacoes/transferir.html.twig', [
                    'form' => $form->createView(),
                ]);
            } 

            // verifica se tem saldo
            $saldoAposOperacaoMaiorQueZero = (($contaOrigem->getSaldo() - $valor) >= 0);
            //dd($contaDestino->getSaldo() - $valor);
            if (!$saldoAposOperacaoMaiorQueZero) {
                // Adiciona uma mensagem de erro ao formulário caso a o saldo vá ficar negativo
                $form->get('valor')->addError(new FormError('Valor indisponível no momento. Seu saldo é de: ' . $contaOrigem->getSaldo()));

                return $this->render('correntistas/transacoes/transferir.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            // Cria um novo objeto Transacoes
            $transferencia->setcontaOrigem($contaOrigem->getId());
            $transferencia->setcontaDestino($contaDestino->getId());
            $transferencia->setUser($userLogado);
            $transferencia->setDataHora(new \DateTime());
            $transferencia->setValor($valor);

            // Persiste o objeto e faz o flush
            $entityManager->persist($transferencia);
            $entityManager->flush();

            $contaOrigem->setSaldo($contaOrigem->getSaldo() - $valor);
            $contas->save($contaOrigem);

            $contaDestino->setSaldo($contaDestino->getSaldo() + $valor);
            $contas->save($contaDestino, true);

            $this->addFlash('notice', 'Transferência realizado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('correntistas/transacoes/transferir.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

/**
 * ADICIONAR TRANSACAO
 * EDITAR TRANSACAO #[IsGranted(Transacoes::EDIT, 'transacao')]
 * EXOBIR TRANSACAO #[IsGranted(Transacoes::VIEW, 'transacao')]
 * LISTAR TRANSACAO 
 * 
 * LISTAR CONTAS
 * 
 */
