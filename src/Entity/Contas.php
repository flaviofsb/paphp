<?php

namespace App\Entity;

use App\Repository\ContasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: ContasRepository::class)]
class Contas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $saldo = 0;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContasTipos $contas_tipos = null;

    #[ORM\OneToMany(mappedBy: 'conta_origem', targetEntity: Transacoes::class)]
    private Collection $transacoes;

    #[ORM\OneToMany(mappedBy: 'conta_destino', targetEntity: Transacoes::class)]
    private Collection $transacoes_recebidas;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime(message: 'É necessário informar uma data e hora válida.')]
    private ?\DateTimeInterface $data_hora_aprovacao = null;

    #[ORM\ManyToOne(inversedBy: 'contas_aprovadas')]
    private ?User $gerente_aprovacao = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]    
    private ?\DateTimeInterface $data_hora_criacao = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime(message: 'É necessário informar uma data e hora válida.')]
    private ?\DateTimeInterface $data_hora_cancelamento = null;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $correntista = null;

    #[ORM\ManyToOne(inversedBy: 'contas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agencias $agencia = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $status = 0;

 
    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaldo(): ?float
    {
        return $this->saldo;
    }

    public function setSaldo(?float $saldo): self
    {
        $this->saldo = $saldo;

        return $this;
    }

    public function getContasTipos(): ?ContasTipos
    {
        return $this->contas_tipos;
    }

    public function setContasTipos(?ContasTipos $contas_tipos): self
    {
        $this->contas_tipos = $contas_tipos;

        return $this;
    }


    public function getDataHoraAprovacao(): ?\DateTimeInterface
    {
        return $this->data_hora_aprovacao;
    }

    public function setDataHoraAprovacao(?\DateTimeInterface $data_hora_aprovacao): self
    {
        $this->data_hora_aprovacao = $data_hora_aprovacao;

        return $this;
    }

    public function getGerenteAprovacao(): ?User
    {
        return $this->gerente_aprovacao;
    }

    public function setGerenteAprovacao(?User $gerente_aprovacao): self
    {
        $this->gerente_aprovacao = $gerente_aprovacao;

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

    public function getCorrentista(): ?User
    {
        return $this->correntista;
    }

    public function setCorrentista(?User $correntista): self
    {
        $this->correntista = $correntista;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

}
