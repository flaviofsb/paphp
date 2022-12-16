<?php

namespace App\Entity;

use App\Repository\TransacoesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransacoesRepository::class)]
class Transacoes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dataHora = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'É necessário informar o tipo.')]
    private ?string $tipo = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'É necessário informar o valor.')]
    #[Assert\Currency(message: 'É necessário informar um valor monetário.')]
    private ?float $valor = null;

    #[ORM\ManyToOne(inversedBy: 'transacoes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contas $conta_origem = null;

    #[ORM\ManyToOne(inversedBy: 'transacoes_recebidas')]
    private ?Contas $conta_destino = null;

    #[ORM\ManyToOne(inversedBy: 'transacoes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataHora(): ?\DateTimeInterface
    {
        return $this->dataHora;
    }

    public function setDataHora(?\DateTimeInterface $dataHora): self
    {
        $this->dataHora = $dataHora;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getContaOrigem(): ?Contas
    {
        return $this->conta_origem;
    }

    public function setContaOrigem(?Contas $conta_origem): self
    {
        $this->conta_origem = $conta_origem;

        return $this;
    }

    public function getContaDestino(): ?Contas
    {
        return $this->conta_destino;
    }

    public function setContaDestino(?Contas $conta_destino): self
    {
        $this->conta_destino = $conta_destino;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
