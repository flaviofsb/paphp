<?php

namespace App\Form;

use App\Entity\Agencias;
use App\Repository\AgenciasRepository;
use App\Entity\Contas;
use Symfony\Component\Form\AbstractType;
use App\Repository\ContasTiposRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContasFormType extends AbstractType
{

    private $contas_tipos;
    private $registry;
    private $agencias;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->contas_tipos = new ContasTiposRepository($registry);
        $this->agencias = new AgenciasRepository($registry);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $contas_tipos = $this->contas_tipos->findAll();
        $agencias = $this->agencias->findAll();
        $builder  
            ->add('agencia', ChoiceType::class, [
                'choices' => $agencias,
                'choice_label' => 'nome',
                'placeholder' => 'Selecione uma agência',
        

            ])          
            ->add('contas_tipos', ChoiceType::class, [
                'choices' => $contas_tipos,
                'choice_label' => 'tipo',
                'placeholder' => 'Selecione um tipo',
           

            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contas::class,
        ]);
    }
}
