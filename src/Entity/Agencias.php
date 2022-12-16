<?php

namespace App\Entity;

use App\Repository\AgenciasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AgenciasRepository::class)]
class Agencias
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar a agência.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $nome = null;

    #[ORM\OneToOne(inversedBy: 'agencias_gerenciadas', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $gerente = null;

    #[ORM\OneToMany(mappedBy: 'agencia', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar o numero da agência.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $numero = null;

    #[Assert\NotBlank(message: 'É necessário informar o telefone.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    #[ORM\Column(length: 255)]
    private ?string $telefone = null;

    #[Assert\NotBlank(message: 'É necessário informar o endereço.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    #[ORM\Column(length: 255)]
    private ?string $logradouro = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complemento = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero_endereco = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cep = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar o bairro.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $bairro = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar a cidade.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $cidade = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar o estado.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $uf = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getGerente(): ?User
    {
        return $this->gerente;
    }

    public function setGerente(User $gerente): self
    {
        $this->gerente = $gerente;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setAgencia($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgencia() === $this) {
                $user->setAgencia(null);
            }
        }

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): self
    {
        $this->telefone = $telefone;

        return $this;
    }

    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    public function setLogradouro(string $logradouro): self
    {
        $this->logradouro = $logradouro;

        return $this;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    public function setComplemento(?string $complemento): self
    {
        $this->complemento = $complemento;

        return $this;
    }

    public function getNumeroEndereco(): ?string
    {
        return $this->numero_endereco;
    }

    public function setNumeroEndereco(?string $numero_endereco): self
    {
        $this->numero_endereco = $numero_endereco;

        return $this;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function setCep(?string $cep): self
    {
        $this->cep = $cep;

        return $this;
    }

    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    public function setBairro(string $bairro): self
    {
        $this->bairro = $bairro;

        return $this;
    }

    public function getCidade(): ?string
    {
        return $this->cidade;
    }

    public function setCidade(string $cidade): self
    {
        $this->cidade = $cidade;

        return $this;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(string $uf): self
    {
        $this->uf = $uf;

        return $this;
    }

    

}
