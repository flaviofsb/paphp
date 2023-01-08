<?php
namespace App\Form;

use App\Entity\Transacoes;
use Doctrine\DBAL\Connection;
use App\Repository\ContasRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransacoesTransferirFormType extends AbstractType
{
    private $connection;
    private $token;
    private $contas;
    private $registry;
    public function __construct(Connection $connection, TokenStorageInterface $token, ManagerRegistry $registry)
    {
        $this->connection = $connection;
        $this->token = $token;
        $this->registry = $registry;
        $this->contas = new ContasRepository($registry);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var \App\Entity\User $user */
        $userLogado = $this->token->getToken()->getUser();
        
        $contasAnteriores = $this->contas->findBy(['correntista' => $userLogado->getId(), 'status' => 1, ]);
        //dd($contasAnteriores);
        $builder
        ->add('conta_destino', TextType::class, ['required' => true,
        'constraints' => [
            new NotBlank([
                'message' => 'Preencha o número da conta para transferência.',
            ]),
        ],
        'error_bubbling' => false,
        ])
        ->add('conta_origem', ChoiceType::class, [
            'required' => true,
            'choices' => $contasAnteriores,
            'choice_label' => 'id',
            'choice_value' => 'id',
            'placeholder' => 'Selecione a conta de origem',            
            'constraints' => [
                new NotBlank([
                    'message' => 'Selecione a conta de origem',
                ]),
            ],])     
       
          
        ->add('valor', MoneyType::class, [
            'required' => true,
            'currency' => '',
            'divisor' => 1,
        ])
        ;
        $builder->get('conta_origem')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transacoes::class,
        ]);
       
    }
}
