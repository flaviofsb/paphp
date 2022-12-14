<?php

namespace App\Entity;

use App\Repository\ContasTiposRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContasTiposRepository::class)]
class ContasTipos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar o tipo.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $tipo = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
