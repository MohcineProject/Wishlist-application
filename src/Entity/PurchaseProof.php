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

    #[ORM\OneToOne(inversedBy: 'purchaseProof', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'purchaseProofs',cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $buyer = null;

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

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(Item $item): static
    {
        $this->item = $item;
        $this->item_id = $item->getId(); // Keep item_id in sync
        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    public function setItemId(int $item_id): static
    {
        $this->item_id = $item_id;
        return $this;
    }
}