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
    #[Assert\NotBlank(message: 'É necessário informar a data de criação.')]
    #[Assert\DateTime(message: 'É necessário informar uma data e hora válida.')]
    private ?\DateTimeInterface $data_hora_criacao = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\DateTime(message: 'É necessário informar uma data e hora válida.')]
    private ?\DateTimeInterface $data_hora_cancelamento = null;

    public function __construct()
    {
        $this->transacoes = new ArrayCollection();
        $this->transacoes_recebidas = new ArrayCollection();
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
            $transaco->setContaOrigem($this);
        }

        return $this;
    }

    public function removeTransaco(Transacoes $transaco): self
    {
        if ($this->transacoes->removeElement($transaco)) {
            // set the owning side to null (unless already changed)
            if ($transaco->getContaOrigem() === $this) {
                $transaco->setContaOrigem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transacoes>
     */
    public function getTransacoesRecebidas(): Collection
    {
        return $this->transacoes_recebidas;
    }

    public function addTransacoesRecebida(Transacoes $transacoesRecebida): self
    {
        if (!$this->transacoes_recebidas->contains($transacoesRecebida)) {
            $this->transacoes_recebidas->add($transacoesRecebida);
            $transacoesRecebida->setContaDestino($this);
        }

        return $this;
    }

    public function removeTransacoesRecebida(Transacoes $transacoesRecebida): self
    {
        if ($this->transacoes_recebidas->removeElement($transacoesRecebida)) {
            // set the owning side to null (unless already changed)
            if ($transacoesRecebida->getContaDestino() === $this) {
                $transacoesRecebida->setContaDestino(null);
            }
        }

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
}
