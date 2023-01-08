<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\UserRepository;
use App\Entity\Agencias;
use Doctrine\Persistence\ManagerRegistry;

class AgenciasEdicaoGerenciaFormType extends AbstractType
{
    private $users;
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->users = new UserRepository($registry);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $gerentes = $this->users->findUsersByRole('ROLE_GERENTE');  
        //$gerentes = $this->users->retornarGerentesSemAgencia();  
        //dd($gerentes);
        
        $builder
            ->add('nome')
            ->add('numero')
            ->add('telefone')
            ->add('logradouro')
            ->add('complemento')
            ->add('numero_endereco')
            ->add('cep')
            ->add('bairro')
            ->add('cidade')
            ->add('uf')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agencias::class,
        ]);
    }
}
