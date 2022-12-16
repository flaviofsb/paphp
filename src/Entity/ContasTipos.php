<?php

namespace App\Entity;

use App\Repository\ContasTiposRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'contas_tipos', targetEntity: Contas::class)]
    private Collection $contas;

    public function __construct()
    {
        $this->contas = new ArrayCollection();
    }

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
            $conta->setContasTipos($this);
        }

        return $this;
    }

    public function removeConta(Contas $conta): self
    {
        if ($this->contas->removeElement($conta)) {
            // set the owning side to null (unless already changed)
            if ($conta->getContasTipos() === $this) {
                $conta->setContasTipos(null);
            }
        }

        return $this;
    }
}
