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
    #[Assert\NotBlank(message: 'É necessário informar o tipo.')]
    private ?float $valor = null;

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
}
