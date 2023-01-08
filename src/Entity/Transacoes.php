<?php

namespace App\Entity;

use App\Repository\TransacoesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransacoesRepository::class)]
class Transacoes
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';

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
    private ?float $valor = null;


    #[ORM\ManyToOne(inversedBy: 'transacoes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conta_origem = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conta_destino = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContaOrigem(): ?string
    {
        return $this->conta_origem;
    }

    public function setContaOrigem(?string $conta_origem): self
    {
        $this->conta_origem = $conta_origem;

        return $this;
    }

    public function getContaDestino(): ?string
    {
        return $this->conta_destino;
    }

    public function setContaDestino(?string $conta_destino): self
    {
        $this->conta_destino = $conta_destino;

        return $this;
    }
}
