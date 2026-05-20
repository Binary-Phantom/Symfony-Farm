<?php

namespace App\Entity;

use App\Repository\VeterinarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: VeterinarioRepository::class)]

#[UniqueEntity(
    fields: ['crmv'],
    message: 'Este CRMV já está em uso.'
)]

class Veterinario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'O nome é obrigatório.'
    )]
    #[Assert\Length(
        min: 3,
        max: 255
    )]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'O CRMV é obrigatório.'
    )]
    #[Assert\Length(
        min: 4,
        max: 20
    )]
    private ?string $crmv = null;

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

    public function getCrmv(): ?string
    {
        return $this->crmv;
    }

    public function setCrmv(string $crmv): static
    {
        $this->crmv = $crmv;

        return $this;
    }
    public function __toString(): string
    
    {
    
        return $this->nome . ' - CRMV: ' . $this->crmv;
    }
}