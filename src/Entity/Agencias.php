<?php

namespace App\Entity;

use App\Repository\AgenciasRepository;
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
    private ?string $agencia = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgencia(): ?string
    {
        return $this->agencia;
    }

    public function setAgencia(string $agencia): self
    {
        $this->agencia = $agencia;

        return $this;
    }
}
