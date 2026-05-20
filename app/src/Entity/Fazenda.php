<?php

namespace App\Entity;

use App\Repository\FazendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: FazendaRepository::class)]

#[UniqueEntity(
    fields: ['nome'],
    message: 'Já existe uma fazenda com este nome.'
)]

class Fazenda
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $nome = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $tamanho = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $responsavel = null;

    #[ORM\ManyToMany(targetEntity: Veterinario::class)]
    #[Assert\Count(
        min: 1,
        minMessage: 'Selecione pelo menos um veterinário.'
    )]
    private Collection $veterinarios;

    #[ORM\OneToMany(
        mappedBy: 'fazenda',
        targetEntity: Gado::class
    )]
    private Collection $gados;

    public function __construct()
    {
        $this->veterinarios = new ArrayCollection();
        $this->gados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getTamanho(): ?float
    {
        return $this->tamanho;
    }

    public function setTamanho(float $tamanho): static
    {
        $this->tamanho = $tamanho;

        return $this;
    }

    public function getResponsavel(): ?string
    {
        return $this->responsavel;
    }

    public function setResponsavel(string $responsavel): static
    {
        $this->responsavel = $responsavel;

        return $this;
    }

    public function getVeterinarios(): Collection
{
    return $this->veterinarios;
}

public function addVeterinario(Veterinario $veterinario): static
{
    if (!$this->veterinarios->contains($veterinario)) {

        $this->veterinarios->add($veterinario);

    }

    return $this;
}

public function removeVeterinario(
    Veterinario $veterinario
): static {

    $this->veterinarios->removeElement($veterinario);

    return $this;
}

    public function getGados(): Collection
    {
        return $this->gados;
    }
}