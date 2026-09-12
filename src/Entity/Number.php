<?php

namespace App\Entity;

use App\Repository\NumberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NumberRepository::class)]
class Number
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(length: 100)]
    private ?string $searchTarget = null;

    #[ORM\Column(length: 20)]
    private ?string $foundIndex = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getSearchTarget(): ?string
    {
        return $this->searchTarget;
    }

    public function setSearchTarget(string $searchTarget): static
    {
        $this->searchTarget = $searchTarget;

        return $this;
    }

    public function getFoundIndex(): ?string
    {
        return $this->foundIndex;
    }

    public function setFoundIndex(?string $foundIndex): static
    {
        $this->foundIndex = $foundIndex;

        return $this;
    }
}