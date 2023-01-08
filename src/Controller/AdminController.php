<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Agencias;
use App\Entity\ContasTipos;
use App\Form\AgenciasFormType;
use App\Form\ContasTiposFormType;
use App\Repository\UserRepository;
use App\Form\AgenciasEditarFormType;
use App\Repository\ContasRepository;
use App\Repository\AgenciasRepository;
use App\Repository\TransacoesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContasTiposRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/transacoes', name: 'app_admin_transacoes_listar')]
    public function listar(TransacoesRepository $transacoes, EntityManagerInterface $entityManager): Response
    {
        
        //dump($transacoesArrayBusca);
        $transacoes = $transacoes->findAll();
        //$transacoes = $transacoes->findBy(['user' => $userAutenticado->getId()]);
        //dd($transacoes);
        return $this->render('admin/transacoes/listar.html.twig', [
            'transacoes' => $transacoes
        ]);
    }
    
    #[Route('/admin/gerentes', name: 'app_admin_gerentes_listar')]
    public function listarGerentes(UserRepository $users): Response
    {

        $gerentes = $users->findUsersByRole('ROLE_GERENTE');  

        //dd($gerentes);
        return $this->render('admin/gerentes/listar.html.twig', [
            'controller_name' => 'AdminController',
            'gerentes' => $gerentes,
        ]);
    }

    #[Route('/admin/agencias', name: 'app_admin_agencias_listar')]
    public function listarAgencias(AgenciasRepository $agencias): Response
    {
        $agencias = $agencias->findAll();
        //dd($agencias);

        return $this->render('admin/agencias/listar.html.twig', [
            'controller_name' => 'AdminController',
            'agencias' => $agencias,
        ]);
    }


    
    #[Route('/admin/agencias/cadastrar', name: 'app_admin_cadastrar_agencias')]
    public function cadastrarAgencias(Request $request, AgenciasRepository $agencias, UserRepository $gerentes): Response
    {
        $agencia = new Agencias();   
         

        $form = $this->createForm(AgenciasFormType::class, new Agencias());
        $form->handleRequest($request);
        
       // dd($gerente);
        if ($form->isSubmitted() && $form->isValid()) {
            $gerente = $form->getData()->getGerente()->getId();     
        
            $gerente = $gerentes->findOneBy(['id' => $gerente]);

            $agencia = $form->getData();
            // salvar e dar flush

            //dd($agencia);
            // do anything else you need here, like send an email
            $agencias->save($agencia, true);

            $gerente->setAgencia($agencia);
            $gerentes->save($gerente, true);


            $this->addFlash('notice', 'Agência cadastrada com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/agencias/cadastrar.html.twig', [
            'form' => $form->createView(),
            //'gerentes[]' => $gerentes,
        ]);
    }

    

    #[Route('/admin/agencias/editar/{agencia}', name: 'app_admin_editar_agencias')]
    public function editarAgencias(Agencias $agencia, Request $request, AgenciasRepository $agencias): Response
    {
             

        $form = $this->createForm(AgenciasEditarFormType::class, $agencia);
        $form->handleRequest($request);
        //dd($form->getData());
        if ($form->isSubmitted() && $form->isValid()) {


            $agencia = $form->getData();
            // salvar e dar flush

            //dd($agencia);
            // do anything else you need here, like send an email
            $agencias->save($agencia, true);
            $this->addFlash('notice', 'Agência atualizada com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/agencias/editar.html.twig', [
            'form' => $form->createView(),
            //'gerentes[]' => $gerentes,
        ]);
    }

    #[Route('/admin/tipos', name: 'app_admin_tipos_listar')]
    public function listarContasTipos(ContasTiposRepository $tipos): Response
    {
        $tipos = $tipos->findAll();
        //dd($agencias);

        return $this->render('admin/contas_tipos/listar.html.twig', [
            'controller_name' => 'AdminController',
            'tipos' => $tipos,
        ]);
    }

    
    #[Route('/admin/tipos/cadastrar', name: 'app_admin_cadastrar_tipos')]
    public function cadastrarTipos(Request $request, ContasTiposRepository $tipos): Response
    {
        $objTipo = new ContasTipos();
        
        $form = $this->createForm(ContasTiposFormType::class, new ContasTipos());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $objTipo = $form->getData();
            // salvar e dar flush
            // do anything else you need here, like send an email
            $tipos->save($objTipo, true);
            $this->addFlash('notice', 'Tipo de conta cadastrado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/contas_tipos/cadastrar.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }

    
    #[Route('/admin/tipos/editar/{objTipo}', name: 'app_admin_editar_tipos')]
    public function editarTipos(ContasTipos $objTipo, Request $request, ContasTiposRepository $tipos): Response
    {
        
        $form = $this->createForm(ContasTiposFormType::class, $objTipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $objTipo = $form->getData();
            // salvar e dar flush
            // do anything else you need here, like send an email
            $tipos->save($objTipo, true);
            $this->addFlash('notice', 'Tipo de conta atualizado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('admin/contas_tipos/editar.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }
    #[Route('/admin/contas', name: 'app_admin_contas_listar')]
    public function listarContas(ContasRepository $contas): Response
    {
      
        $contas = $contas->findAll();
        //dd($contas);
        return $this->render('admin/contas/listar.html.twig', [
            'controller_name' => 'AdminController',
            'contas' => $contas,
        ]);
    }
}
