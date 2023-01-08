<?php

namespace App\Form;

use App\Entity\User;

use App\Entity\Agencias;
use App\Repository\AgenciasRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationCorrentistasFormType extends AbstractType
{
    private $registry;
    private $agencias;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->agencia = new AgenciasRepository($registry);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$agencia = $this->agencia->findAll();
        //dd($agencia);
        $builder
            
            ->add('nome',null, [
            'mapped' => true])
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'As senhas não conferem',
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => [
                    'label' => 'Senha',
                    'mapped' => 'false'
                ],
                'second_options' => [
                    'label' => 'Repita a senha',
                    'mapped' => 'false'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Informe uma senha',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Sua senha deve ter ao menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
