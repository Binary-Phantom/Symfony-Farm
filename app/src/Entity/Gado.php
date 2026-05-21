<?php

namespace App\Entity;

use App\Repository\GadoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GadoRepository::class)]
class Gado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $codigo = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?float $leiteSemana = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $racaoSemana = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?float $peso = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual(
        value: 'today',
        message: 'A data não pode ser futura.'
    )]
    private ?\DateTimeInterface $nascimento = null;

    #[ORM\Column]
    private ?bool $abatido = false;

    #[ORM\ManyToOne(
        inversedBy: 'gados'
    )]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(
        message: 'Selecione uma fazenda.'
    )]
    private ?Fazenda $fazenda = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getLeiteSemana(): ?float
    {
        return $this->leiteSemana;
    }

    public function setLeiteSemana(float $leiteSemana): static
    {
        $this->leiteSemana = $leiteSemana;

        return $this;
    }

    public function getRacaoSemana(): ?float
    {
        return $this->racaoSemana;
    }

    public function setRacaoSemana(float $racaoSemana): static
    {
        $this->racaoSemana = $racaoSemana;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(float $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getNascimento(): ?\DateTimeInterface
    {
        return $this->nascimento;
    }

    public function setNascimento(\DateTimeInterface $nascimento): static
    {
        $this->nascimento = $nascimento;

        return $this;
    }

    public function isAbatido(): ?bool
    {
        return $this->abatido;
    }

    public function setAbatido(bool $abatido): static
    {
        $this->abatido = $abatido;

        return $this;
    }

    public function getFazenda(): ?Fazenda
    {
        return $this->fazenda;
    }

    public function setFazenda(?Fazenda $fazenda): static
    {
        $this->fazenda = $fazenda;

        return $this;
    }
}