<?php
namespace App\Form;

use App\Entity\Transacoes;
use Doctrine\DBAL\Connection;
use App\Repository\ContasRepository;
use App\Repository\TransacoesRepository;
use Doctrine\ORM\EntityManagerInterface;
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

class TransacoesGerenciaDepositarFormType extends AbstractType
{
    private $connection;
    private $token;
    private $entityManager;
    private $transacoes;
    private $contas;
    
    private $registry;
    public function __construct(Connection $connection, TokenStorageInterface $token, ManagerRegistry $registry, TransacoesRepository $transacoes, EntityManagerInterface $entityManager)
    {
        $this->connection = $connection;
        $this->entityManager = $entityManager;
        $this->transacoes = $transacoes;
        $this->token = $token;
        $this->registry = $registry;
        $this->contas = new ContasRepository($registry);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var \App\Entity\User $user */
        
        $userLogado = $this->token->getToken()->getUser();
        

        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT contas.id FROM contas, agencias 
        WHERE contas.status = 1 AND contas.agencia_id = agencias.id AND agencias.id = ' . $userLogado->getAgencia()->getId();
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        //$result->fetchAllAssociative();
        //dump($sql);
        $contasArray = $result->fetchAllAssociative();
        //dump($contasArray);
        //ajuste no array para pegar so os id das transacoes da agencia
        foreach ($contasArray as $t) {
            $contasArrayBusca[] = $t['id'];
        }
        //dump($contasArrayBusca);
        $contasAnteriores = $this->contas->findBy(array('id' => $contasArrayBusca));

        //$contasAnteriores = "";
        //$contasAnteriores = $this->contas->findBy(['correntista' => $userLogado->getId(), 'status' => 1, ]);
        //dd($contasAnteriores);
        $builder
        
        ->add('conta_destino', ChoiceType::class, [
            'required' => true,
            'choices' => $contasAnteriores,
            'choice_label' => 'id',
            'choice_value' => 'id',
            'placeholder' => 'Selecione a conta para depósito',            
            'constraints' => [
                new NotBlank([
                    'message' => 'Selecione a conta para depósito',
                ]),
            ],])     
       
          
        ->add('valor', MoneyType::class, [
            'required' => true,
            'currency' => '',
            'divisor' => 1,
        ])
        ;
        $builder->get('conta_destino')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transacoes::class,
        ]);
       
    }
}
