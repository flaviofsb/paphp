<?php

namespace App\Entity;

use App\Repository\BancoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BancoRepository::class)]
class Banco
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'É necessário informar o banco.')]
    #[Assert\Length(min:1, max:255, minMessage: 'É necessário informar até 255 caracteres.')]
    private ?string $banco = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function setBanco(string $banco): self
    {
        $this->banco = $banco;

        return $this;
    }
}
