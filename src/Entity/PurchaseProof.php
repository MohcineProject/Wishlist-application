<?php

namespace App\Entity;

use App\Repository\PurchaseProofRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseProofRepository::class)]
class PurchaseProof
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $congratsText = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCongratsText(): ?string
    {
        return $this->congratsText;
    }

    public function setCongratsText(string $congratsText): static
    {
        $this->congratsText = $congratsText;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
