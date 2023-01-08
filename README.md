# Qual o aplicação deve ser criada?
O sistema que foi criado compreende uma aplicação para gerenciamento de uma rede de agências bancárias. A aplicação deve permitir que um banco gerencie suas múltiplas agências. Cada agência tem apenas um gerente responsável e pode ter diferentes tipos de contas, que são associadas com pelo menos um cliente. As transações tradicionais devem ser capazes de ser realizadas entre contas, como debitar, creditar, e transferir. Uma transação pode ser realizada pelo cliente associado com a conta ou pelo gerente responsável pela conta na agência. Todas as transações realizadas, envolvendo qualquer tipo de conta, devem ser registradas, com relação ao tipo e responsável por realizar a transação, para prestação de contas. 

##Usuários do sistema
###Público em geral
O público em geral deve ser capaz de visualizar a lista de agências bancárias e as informações básicas associadas com as agências, como número, nome, endereço, telefone, e gerente responsável, entre outros. Também deve ser possível efetuar depósitos em qualquer conta válida do banco, simulando o que seria feito em um caixa eletrônico.
###Gerentes
Gerentes responsáveis por uma agência tem o poder de efetuar qualquer transação relacionada com contas que pertençam à tal agência, bem como de fazer alterações cadastrais nos dados da agência e clientes associados. O gerente também pode ver todo o histórico de transações da agência.
###Clientes
Um cliente pode iniciar transações a partir de suas contas, e pode ver a lista de transações associadas a cada uma de suas contas. 
###Administrador
Uma única conta do sistema deve ter o poder de criar agências e associar gerentes a agências. Pode visualizar todas as informações das agências e contas, mas não pode efetuar transações.

## Casos de Uso básicos esperados
## Criar agência: 
O administrador da aplicação deve ser capaz de incluir novas agências e gerentes associados a esta.
## Abrir conta: 
Um usuário da aplicação, mesmo que ainda não seja cliente, deve ser capaz de solicitar a abertura de nova conta em uma determinada agência. Esta solicitação deve ser aprovada pelo gerente responsável da agência. Um cliente já existente do banco pode abrir nova conta, sem precisar de aprovação do gerente.

## Transações financeiras: 
Como informado, as transações usuais envolvendo contas devem ser realizadas, como debitar, creditar, e transferir. Um gerente responsável por uma agência pode realizar estas operações para contas pertencentes à agência. Clientes podem iniciar transações a partir de suas contas, que devem ser validadas de acordo com saldo corrente em conta. Cada transação deve ser acompanhada de uma breve descrição em texto descrevendo seu propósito. Finalmente, usuários não autenticados devem ser capazes de efetuar depósitos.

## Visualizar extrato: 
Um cliente deve ser capaz de visualizar seu extrato de transações bancárias. 
Encerrar conta: Um cliente do banco pode solicitar para encerrar uma de suas contas.
<br>
------------
# Solução proposta: Banco Residente
Projeto realizado para a disciplina de programação avançada em PHP da pós-graduação e residência em desenvolvimento de software da UFPE/EMPREL!
Este é um sistema desenvolvido com PHP e frameworks como o Symfony,Twig e Tailwind css, além do banco de dados MySQL.
<br>
------------
# Requisitos para aplicação

- PHP 8.1+
- Composer
- MYSQL 5.7
- Apache 2+
- Symfony 6
------------
# Instalação
1. baixar ou clonar https://github.com/flaviofsb/paphp
2. criar o banco de dados chamado **paphp** 
3. alterar o arquivo **.env linha 27** com o usuário, senha do mysql 
> DATABASE_URL="mysql://root@127.0.0.1:3306/paphp"

4. Entrar na pasta do projeto para rodar as migrations e gerar as tabelas no banco de dados
`php bin/console doctrine:migrations:migrate`
5. na pasta do projeto rodar o composer
`composer install`
6. alterar o arquivo **src/Command/CriaAdminCommand.php linha 38 e 39** com o email e senha desejada para a administração
> $email = "flavio@afixo.com.br";
      $password = "123456";
7.  Rodar o comando para criar o admin do sistema

`php bin/console app:criar_admin`

<br />
------------
# Principais estruturas
## Entidades
### Agencias;
> private ?int $id = null;
private ?string $nome = null;
private ?User $gerente = null;
private Collection $users;
private ?string $numero = null;
private ?string $telefone = null;
private ?string $logradouro = null;
private ?string $complemento = null;
private ?string $numero_endereco = null;
private ?string $cep = null;
private ?string $bairro = null;
private ?string $cidade = null;
private ?string $uf = null;
private Collection $contas;

### Contas;
> private ?int $id = null;
> private ?float $saldo = 0;
> private ?ContasTipos $contas_tipos = null;
> private Collection $transacoes;
> private Collection $transacoes_recebidas;
> private ?\DateTimeInterface $data_hora_aprovacao = null;
> private ?User $gerente_aprovacao = null;
> private ?\DateTimeInterface $data_hora_criacao = null;
> private ?\DateTimeInterface $data_hora_cancelamento = null;
> private ?User $correntista = null;
> private ?Agencias $agencia = null;
> private ?int $status = 0;

### ContasTipos;
> private ?int $id = null;
> private ?string $tipo = null;
> private Collection $contas;

### Transacoes;
> private ?int $id = null;
> private ?\DateTimeInterface $dataHora = null;
> private ?string $tipo = null;
> private ?float $valor = null;
> private ?User $user = null;
> private ?string $conta_origem = null;
> private ?string $conta_destino = null;

### User;
> private ?int $id = null;
> private ?string $email = null;
> private ?string $nome = null;
> private array $roles = [];
> private ?string $password = null;
> private ?Agencias $agencias_gerenciadas = null;
> private ?Agencias $agencia = null;
> private Collection $transacoes;
> private Collection $contas_aprovadas;
> private ?\DateTimeInterface $data_hora_criacao = null;
> private ?\DateTimeInterface $data_hora_cancelamento = null;
> private Collection $contas;
<br />
------------
## Repositórios
Os repositórios **AgenciasRepository, ContasRepository, ContasTiposRepository, TransacoesRepository,  UserRepository** seguem o padrão do symfony 6.

### Como exemplo segue o repositorio de agências

    namespace App\Repository;
    use App\Entity\Agencias;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * @extends ServiceEntityRepository<Agencias>
     *
     * @method Agencias|null find($id, $lockMode = null, $lockVersion = null)
     * @method Agencias|null findOneBy(array $criteria, array $orderBy = null)
     * @method Agencias[]    findAll()
     * @method Agencias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class AgenciasRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Agencias::class);
        }

        public function save(Agencias $entity, bool $flush = false): void
        {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }

        public function remove(Agencias $entity, bool $flush = false): void
        {
            $this->getEntityManager()->remove($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }
    }


<br />
------------
## Controllers
<br />
------------
### AdminController;
Classe responsável por reunir os métodos referentes a administração do sistema;

    class AdminController extends AbstractController
    {
        #[Route('/admin/transacoes', name: 'app_admin_transacoes_listar')]
        public function listar(TransacoesRepository $transacoes, EntityManagerInterface $entityManager): Response
        {
		
		// método responsável por listar todas as transações do banco
		
        }
        #[Route('/admin/agencias', name: 'app_admin_agencias_listar')]
        public function listarAgencias(AgenciasRepository $agencias): Response
        {           
		
		// método responsável por listar todas as agências do banco
		
        }
        #[Route('/admin/agencias/cadastrar', name: 'app_admin_cadastrar_agencias')]
        public function cadastrarAgencias(Request $request, AgenciasRepository $agencias, UserRepository $gerentes): Response
        {            
		
		// método responsável por cadastrar as agências do banco
		
        }
        #[Route('/admin/agencias/editar/{agencia}', name: 'app_admin_editar_agencias')]
        public function editarAgencias(Agencias $agencia, Request $request, AgenciasRepository $agencias): Response
        {
		
		// método responsável por editar as agências do banco
		
        }
        #[Route('/admin/tipos', name: 'app_admin_tipos_listar')]
        public function listarContasTipos(ContasTiposRepository $tipos): Response
        {            
        
		// método responsável por listar os tipos de contas do banco
		
		}       
		
		#[Route('/admin/tipos/cadastrar', name: 'app_admin_cadastrar_tipos')]
        public function cadastrarTipos(Request $request, ContasTiposRepository $tipos): Response
        {            
		
		// método responsável por cadastrar os tipos de contas do banco
				
        }
        #[Route('/admin/tipos/editar/{objTipo}', name: 'app_admin_editar_tipos')]
        public function editarTipos(ContasTipos $objTipo, Request $request, ContasTiposRepository $tipos): Response
        {            
		
		método responsável por editar os tipos de contas do banco
		
        }
        #[Route('/admin/contas', name: 'app_admin_contas_listar')]
        public function listarContas(ContasRepository $contas): Response
        {           
		
		método responsável por listar as contas do banco
		
        }
    }

<br />
------------
### CorrentistasController;
Classe responsável por reunir os métodos referentes aos clientes correntistas do sistema;

    <?php
    class CorrentistasController extends AbstractController

    {
        private $contas;

        #[Route('/correntistas/contas/cancelar/{conta}', name: 'app_correntistas_cancelar_contas')]
        public function aprovarContas(Contas $conta, Request $request, ContasRepository $contas): Response
        {

		//método responsável pelo cancelamento dos correntistas
           
        }

        #[Route('/correntistas/contas', name: 'app_correntistas_contas_listar')]
        public function listarContas(ContasRepository $contas): Response
        {
		
		//método responsável por listar as contas cadastradas dos correntistas
            
        }

        #[Route('/correntistas/contas/cadastrar', name: 'app_correntista_cadastrar_contas')]
        public function cadastrarContas(Request $request, ContasRepository $contas, EntityManagerInterface $entityManager): Response
        {
		
		//método responsável por cadastrar as contas dos correntistas
           
        }

        #[Route('/correntistas/transacoes', name: 'app_correntistas_transacoes_listar')]
        public function listarTransacoes(TransacoesRepository $transacoes): Response
        {
		
		//método responsável por listar as contas cadastradas
           
        }

        #[Route('/correntistas/transacoes/cadastrar', name: 'app_correntista_cadastrar_transacao')]
        public function exibirCadastro(Request $request): Response
        {
           
		   //método responsável por listar as opções de transações
		   
        }

        #[Route('/correntistas/transacoes/depositar', name: 'app_correntista_depositar_transacao')]
        public function depositarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
        {

		//método responsável para que os correntistas possam realizar os depósitos 
            
        }
        #[Route('/correntistas/transacoes/sacar', name: 'app_correntista_sacar_transacao')]
        public function sacarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
        {

		//método responsável para que os correntistas possam realizar os saques 
            
        }

        #[Route('/correntistas/transacoes/transferir', name: 'app_correntista_transferir_transacao')]
        public function transferirTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
        {
		
		//método responsável para que os correntistas possam realizar as transeferências 
            
        }
    }

<br />
------------
### GerenciaController;
Classe responsável por reunir os métodos referentes a administração do sistema;
	class GerenciaController extends AbstractController
	{

		#[Route('/gerencia/transacoes', name: 'app_gerencia_transacoes_listar')]
		public function listar(TransacoesRepository $transacoes, EntityManagerInterface $entityManager): Response
		{
		
		//método responsável para que os gerentes possam listar as transações 
		
		}

		#[Route('/gerencia/transacoes/cadastrar', name: 'app_gerencia_cadastrar_transacao')]
		public function exibirCadastro(Request $request): Response
		{
		
		//método que exibe para os gerentes as opções de cadastrar as transações
		
		}


		#[Route('/gerencia/transacoes/depositar', name: 'app_gerencia_depositar_transacao')]
		public function depositarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
		{
		
		//método responsável para que os gerentes possam realizar as transações de depositos
		
		}
		#[Route('/gerencia/transacoes/sacar', name: 'app_gerencia_sacar_transacao')]
		public function sacarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
		{
		
		//método responsável para que os gerentes possam realizar as transações de saque
		
		}

		#[Route('/gerencia/transacoes/transferir', name: 'app_gerencia_transferir_transacao')]
		public function transferirTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
		{
		
		//método responsável para que os gerentes possam realizar as transações de transferencia
		
		}


		#[Route('/gerencia/agencia/', name: 'app_gerencia_agencia_editar')]
		public function editarAgenciaGerencia(UserRepository $gerentes, AgenciasRepository $agencias, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
		{
		
		//método responsável para que os gerentes possam realizar as alteração nos dados de sua agência
		
		}

		#[Route('/gerencia/contas', name: 'app_gerencia_contas_listar')]
		public function listarContas(ContasRepository $contas): Response
		{
		
		//método responsável para que os gerentes possam listar as contas/correntistas da agência a qual ele pertence
		
		}

		#[Route('/gerencia/contas/aprovar/{conta}', name: 'app_gerencia_aprovar_contas')]
		public function aprovarContas(Contas $conta, Request $request, ContasRepository $contas): Response
		{
		
		//método responsável para que os gerentes possam aprovar as contas  dos correntistas da agência a qual ele pertence
		
		}
	}


<br />
------------
### PaphpController;
Classe responsável por reunir os métodos referentes a administração do sistema;
    class PaphpController extends AbstractController
    {
        #[Route('/transacoes/depositar', name: 'app_depositar_padrao')]
        public function depositarTransacao(Request $request, TransacoesRepository $transacoes, EntityManagerInterface $entityManager, ContasRepository $contas): Response
        {
		
		// metodo responsável pela operação de deposito por um usuário sem estar logado
		
        }
        
        #[Route('/', name: 'app_index')]
        public function index(AgenciasRepository $agencias): Response
        {
		
		// tela inicial do sistema
		
        }
        #[Route('/login', name: 'app_login')]
        public function indexLogin(): Response
        {
		
		metodo responsavel pelo login
		
        }

        #[Route('/retorno_login', name: 'app_retorno_login')]
        public function retornoLogin(): Response
        {
		
		// metodo responsavel por retornar as informações do login
		
        }
        #[Route('/logout', name: 'app_logout')]
        public function logout()
        {
		
		// metodo responsavel pelo logout
		
		}
    }


<br />
------------
### RegistrationController;
Classe responsável por reunir os métodos referentes aos registros de usuarios do sistema, seja ele administrador, gerente ou correntista;
    class RegistrationController extends AbstractController
    {
        #[Route('/cadastrar', name: 'app_cadastrar_correntistas')]
        public function cadastrarCorrentistas(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
            //metodo responsavel pelo cadastro dos usuarios clientes
        }

        #[Route('/gerencia/correntistas/editar/{correntista}', name: 'app_gerencia_editar_correntistas')]
        public function editarCorrentistasGerencia(User $correntista, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
            //metodo responsavel por propiciar a gerencia edição dos usuarios clientes
        }

        #[Route('/correntistas/editar/', name: 'app_correntistas_editar')]
        public function editarCorrentistas(UserRepository $correntistas, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
            //metodo responsavel por propiciar a edição dos usuarios por parte dos clientes
        }
        
        #[Route('/admin/gerentes/cadastrar', name: 'app_admin_cadastrar_gerentes')]
        public function cadastrarGerentes(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
            //metodo responsavel pelo cadastro dos usuarios gerentes executado pelo administrador
        }

        #[Route('/admin/gerentes/editar/{gerente}', name: 'app_admin_editar_gerentes')]
        public function editarGerentes(User $gerente, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
           //metodo responsavel pelo edição dos usuarios gerentes por parte da administração
        }

        #[Route('/gerencia/editar/', name: 'app_gerencia_editar')]
        public function editarGerentesGerencia(UserRepository $gerentes, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
           //metodo responsavel pelo edição dos dados dos usuarios gerentes
        }       
    }




# README.md
**Table of Contents**

[TOCM]

[TOC]

###End