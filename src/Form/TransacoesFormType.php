<?php

namespace App\Form;

use App\Entity\Transacoes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransacoesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            
            ->add('valor')
            ->add('conta_origem')
            ->add('conta_destino')
            ->add('tipo', ChoiceType::class, [
                'choices' => [
                    'Depósito' => 'creditar',
                    'Transferência' => 'transferir',
                    'Saque' => 'debitar',
                ],
                'choice_label' => 'tipo',
                'placeholder' => 'Selecione o tipo',
           

            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transacoes::class,
        ]);
    }
}
