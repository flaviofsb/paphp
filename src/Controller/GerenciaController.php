<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contas;
use App\Entity\Agencias;
use App\Entity\Transacoes;
use App\Form\AgenciasFormType;
use App\Repository\UserRepository;
use App\Repository\ContasRepository;
use App\Form\TransacoesSacarFormType;
use Symfony\Component\Form\FormError;
use App\Repository\AgenciasRepository;
use App\Repository\TransacoesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TransacoesDepositarFormType;
use App\Form\TransacoesTransferirFormType;
use App\Form\AgenciasEdicaoGerenciaFormType;
use App\Form\TransacoesGerenciaSacarFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TransacoesGerenciaDepositarFormType;
use App\Form\TransacoesGerenciaTransferirFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GerenciaController extends AbstractController
{



    #[Route('/gerencia/transacoes', name: 'app_gerencia_transacoes_listar')]
    public function listar(TransacoesRepository $transacoes, EntityManagerInterface $entityManager): Response
    {
        // dd($postID);

        $userAutenticado = $this->getUser();

        //dump($userAutenticado->getAgenciasGerenciadas()->getId());
        $conn = $entityManager->getConnection();
        $sql = 'SELECT transacoes.id FROM transacoes, contas, agencias 
        WHERE transacoes.conta_destino = contas.id AND contas.agencia_id = agencias.id AND agencias.id = ' . $userAutenticado->getAgenciasGerenciadas()->getId();
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        //$result->fetchAllAssociative();

        $transacoesArray = $result->fetchAllAssociative();
        //dump($transacoesArray);
        //ajuste no array para pegar so os id das transacoes da agencia
        foreach ($transacoesArray as $t) {
            $transacoesArrayBusca[] = $t['id'];
        }
        //dump($transacoesArrayBusca);
        $transacoes = $transacoes->findBy(array('id' => $transacoesArrayBusca));
        //$transacoes = $transacoes->findBy(['user' => $userAutenticado->getId()]);
        //dd($transacoes);
        return $this->render('gerencia/transacoes/listar.html.twig', [
            'transacoes' => $transacoes
        ]);
    }

    #[Route('/gerencia/transacoes/cadastrar', name: 'app_gerencia_cadastrar_transacao')]
    public function exibirCadastro(Request $request): Response
    {
        return $this->render('gerencia/transacoes/cadastrar.html.twig', [
            'controller_name' => 'GerenciaController',
        ]);
    }


    #[Route('/gerencia/transacoes/depositar', name: 'app_gerencia_depositar_transacao')]
    public function depositarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
    {

        $deposito = new Transacoes();
        $deposito->setTipo("Depósito");
        $form = $this->createForm(TransacoesGerenciaDepositarFormType::class, $deposito);

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

                return $this->render('gerencia/transacoes/depositar.html.twig', [
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

        return $this->render('gerencia/transacoes/depositar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/gerencia/transacoes/sacar', name: 'app_gerencia_sacar_transacao')]
    public function sacarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
    {

        $saque = new Transacoes();
        $saque->setTipo("Saque");
        $form = $this->createForm(TransacoesGerenciaSacarFormType::class, $saque);

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

                return $this->render('gerencia/transacoes/sacar.html.twig', [
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

        return $this->render('gerencia/transacoes/sacar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/gerencia/transacoes/transferir', name: 'app_gerencia_transferir_transacao')]
    public function transferirTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
    {

        $transferencia = new Transacoes();
        $transferencia->setTipo("Transferência");
        $form = $this->createForm(TransacoesGerenciaTransferirFormType::class, $transferencia);

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

                return $this->render('gerencia/transacoes/transferir.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // verifica se tem saldo
            $saldoAposOperacaoMaiorQueZero = (($contaOrigem->getSaldo() - $valor) >= 0);
            //dd($contaDestino->getSaldo() - $valor);
            if (!$saldoAposOperacaoMaiorQueZero) {
                // Adiciona uma mensagem de erro ao formulário caso a o saldo vá ficar negativo
                $form->get('valor')->addError(new FormError('Valor indisponível no momento. Seu saldo é de: ' . $contaOrigem->getSaldo()));

                return $this->render('gerencia/transacoes/transferir.html.twig', [
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

        return $this->render('gerencia/transacoes/transferir.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/gerencia/agencia/', name: 'app_gerencia_agencia_editar')]
    public function editarAgenciaGerencia(UserRepository $gerentes, AgenciasRepository $agencias, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $userAutenticado = $this->getUser();

        $gerenteAtual = $gerentes->findOneBy(['id' => $userAutenticado->getId()]);

        $agencia = $agencias->findOneBy(['id' => $gerenteAtual->getAgenciasGerenciadas()]);


        //dd($agencia);
        $form = $this->createForm(AgenciasEdicaoGerenciaFormType::class, $agencia);
        $form->handleRequest($request);
        //dd($form->getData());
        if ($form->isSubmitted() && $form->isValid()) {


            $agencia = $form->getData();
            // salvar e dar flush
            //dump($agencia);
            //echo "<br><br>-<br><br>";
            //$agencia->setGerente($gerenteAtual);
            // dump($agencia);

            //die;
            // do anything else you need here, like send an email

            $agencias->save($agencia, true);
            $this->addFlash('notice', 'Agência atualizada com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('gerencia/agencias/editar.html.twig', [
            'form' => $form->createView(),
            //'gerentes[]' => $gerentes,
        ]);
    }





    #[Route('/gerencia/correntistas', name: 'app_gerencia_correntistas_listar')]
    public function listarCorrentistas(UserRepository $users): Response
    {


        $userAutenticado = $this->getUser();

        //dd($userAutenticado);
        //dd($userAutenticado->getAgenciasGerenciadas()->getId()); 
        $correntistas = $users->findUsersByRoleAgencia('ROLE_CORRENTISTA', $userAutenticado->getAgenciasGerenciadas()->getId());
        //dd($correntistas);
        //dd($gerentes);
        return $this->render('gerencia/correntistas/listar.html.twig', [
            'controller_name' => 'GerenciaController',
            'correntistas' => $correntistas,
        ]);
    }

    #[Route('/gerencia/contas', name: 'app_gerencia_contas_listar')]
    public function listarContas(ContasRepository $contas): Response
    {
        $userAutenticado = $this->getUser();

        //dd($userAutenticado);
        $contas = $contas->findBy(['agencia' => $userAutenticado->getAgencia()->getId()]);
        //dd($contas);
        return $this->render('gerencia/contas/listar.html.twig', [
            'controller_name' => 'GerenciaController',
            'contas' => $contas,
        ]);
    }



    #[Route('/gerencia/contas/aprovar/{conta}', name: 'app_gerencia_aprovar_contas')]
    public function aprovarContas(Contas $conta, Request $request, ContasRepository $contas): Response
    {

        $userAutenticado = $this->getUser();
        if ($conta) {

            $conta = $contas->findOneBy(['id' => $conta->getId()]);
            $conta->setGerenteAprovacao($userAutenticado);
            $conta->setDataHoraAprovacao(new \DateTime());
            $conta->setStatus(1);
            //dd($conta);
            //$agencia = $form->getData();
            // salvar e dar flush

            //dd($agencia);
            // do anything else you need here, like send an email
            $contas->save($conta, true);
            $this->addFlash('notice', 'Conta aprovada com sucesso.');
            return $this->redirectToRoute('app_index');
        }
    }
}
