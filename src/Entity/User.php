<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: UserRepository::class)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'É necessário informar o e-mail.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar o nome.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $nome = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'gerente', cascade: ['persist', 'remove'])]
    private ?Agencias $agencias_gerenciadas = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Agencias $agencia = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Transacoes::class)]
    private Collection $transacoes;

    #[ORM\OneToMany(mappedBy: 'gerente_aprovacao', targetEntity: Contas::class)]
    private Collection $contas_aprovadas;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data_hora_criacao = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $data_hora_cancelamento = null;

    #[ORM\OneToMany(mappedBy: 'correntista', targetEntity: Contas::class)]
    private Collection $contas;

    public function __construct()
    {
        $this->transacoes = new ArrayCollection();
        $this->contas_aprovadas = new ArrayCollection();
        $this->contas = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAgenciasGerenciadas(): ?Agencias
    {
        return $this->agencias_gerenciadas;
    }

    public function setAgenciasGerenciadas(Agencias $agencias_gerenciadas): self
    {
        // set the owning side of the relation if necessary
        if ($agencias_gerenciadas->getGerente() !== $this) {
            $agencias_gerenciadas->setGerente($this);
        }

        $this->agencias_gerenciadas = $agencias_gerenciadas;

        return $this;
    }

    public function getAgencia(): ?Agencias
    {
        return $this->agencia;
    }

    public function setAgencia(?Agencias $agencia): self
    {
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * @return Collection<int, Transacoes>
     */
    public function getTransacoes(): Collection
    {
        return $this->transacoes;
    }

    public function addTransaco(Transacoes $transaco): self
    {
        if (!$this->transacoes->contains($transaco)) {
            $this->transacoes->add($transaco);
            $transaco->setUser($this);
        }

        return $this;
    }

    public function removeTransaco(Transacoes $transaco): self
    {
        if ($this->transacoes->removeElement($transaco)) {
            // set the owning side to null (unless already changed)
            if ($transaco->getUser() === $this) {
                $transaco->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contas>
     */
    public function getContasAprovadas(): Collection
    {
        return $this->contas_aprovadas;
    }

    public function addContasAprovada(Contas $contasAprovada): self
    {
        if (!$this->contas_aprovadas->contains($contasAprovada)) {
            $this->contas_aprovadas->add($contasAprovada);
            $contasAprovada->setGerenteAprovacao($this);
        }

        return $this;
    }

    public function removeContasAprovada(Contas $contasAprovada): self
    {
        if ($this->contas_aprovadas->removeElement($contasAprovada)) {
            // set the owning side to null (unless already changed)
            if ($contasAprovada->getGerenteAprovacao() === $this) {
                $contasAprovada->setGerenteAprovacao(null);
            }
        }

        return $this;
    }

    public function getDataHoraCriacao(): ?\DateTimeInterface
    {
        return $this->data_hora_criacao;
    }

    public function setDataHoraCriacao(\DateTimeInterface $data_hora_criacao): self
    {
        $this->data_hora_criacao = $data_hora_criacao;

        return $this;
    }

    public function getDataHoraCancelamento(): ?\DateTimeInterface
    {
        return $this->data_hora_cancelamento;
    }

    public function setDataHoraCancelamento(?\DateTimeInterface $data_hora_cancelamento): self
    {
        $this->data_hora_cancelamento = $data_hora_cancelamento;

        return $this;
    }

    /**
     * @return Collection<int, Contas>
     */
    public function getContas(): Collection
    {
        return $this->contas;
    }

    public function addConta(Contas $conta): self
    {
        if (!$this->contas->contains($conta)) {
            $this->contas->add($conta);
            $conta->setCorrentista($this);
        }

        return $this;
    }

    public function removeConta(Contas $conta): self
    {
        if ($this->contas->removeElement($conta)) {
            // set the owning side to null (unless already changed)
            if ($conta->getCorrentista() === $this) {
                $conta->setCorrentista(null);
            }
        }

        return $this;
    }

   

   
}
