<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationGerentesFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RegistrationCorrentistasFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationCorrentistasEdicaoFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/cadastrar', name: 'app_cadastrar_correntistas')]
    public function cadastrarCorrentistas(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationCorrentistasFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRoles(['ROLE_CORRENTISTA']);
            $user->setDataHoraCriacao(new \DateTime());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('notice', 'Cliente cadastrado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/correntistas.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/gerencia/correntistas/editar/{correntista}', name: 'app_gerencia_editar_correntistas')]
    public function editarCorrentistasGerencia(User $correntista, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationGerentesFormType::class, $correntista);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            //$user->setRoles(['ROLE_GERENTE']);
            //$user->setDataHoraCriacao(new \DateTime());
            $correntista->setPassword(
                $userPasswordHasher->hashPassword(
                    $correntista,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($correntista);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('notice', 'Correntista atualizado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/editar_correntistas_gerencia.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/correntistas/editar/', name: 'app_correntistas_editar')]
    public function editarCorrentistas(UserRepository $correntistas, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $correntista = new User();
        $userAutenticado = $this->getUser();

        $correntistaAtual = $correntistas->findOneBy(['id' => $userAutenticado->getId()]);
        
        $form = $this->createForm(RegistrationCorrentistasEdicaoFormType::class, $correntistaAtual);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            //$user->setRoles(['ROLE_GERENTE']);
            //$user->setDataHoraCriacao(new \DateTime());

            //dd($correntistaAtual);
            $correntistaAtual->setPassword(
                $userPasswordHasher->hashPassword(
                    $correntistaAtual,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($correntistaAtual);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('notice', 'Correntista atualizado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/editar_correntistas.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/gerentes/cadastrar', name: 'app_admin_cadastrar_gerentes')]
    public function cadastrarGerentes(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationGerentesFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRoles(['ROLE_GERENTE']);
            $user->setDataHoraCriacao(new \DateTime());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('notice', 'Gerente cadastrado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/gerentes.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/gerentes/editar/{gerente}', name: 'app_admin_editar_gerentes')]
    public function editarGerentes(User $gerente, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationGerentesFormType::class, $gerente);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            //$user->setRoles(['ROLE_GERENTE']);
            //$user->setDataHoraCriacao(new \DateTime());
            $gerente->setPassword(
                $userPasswordHasher->hashPassword(
                    $gerente,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($gerente);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('notice', 'Gerente atualizado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/editar_gerentes.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/gerencia/editar/', name: 'app_gerencia_editar')]
    public function editarGerentesGerencia(UserRepository $gerentes, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $gerente = new User();
        $userAutenticado = $this->getUser();

        $gerenteAtual = $gerentes->findOneBy(['id' => $userAutenticado->getId()]);
        
        $form = $this->createForm(RegistrationGerentesFormType::class, $gerenteAtual);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            //$user->setRoles(['ROLE_GERENTE']);
            //$user->setDataHoraCriacao(new \DateTime());

            //dd($correntistaAtual);
            $gerenteAtual->setPassword(
                $userPasswordHasher->hashPassword(
                    $gerenteAtual,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($gerenteAtual);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('notice', 'Gerente atualizado com sucesso.');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/editar_gerentes_gerencia.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    

}
