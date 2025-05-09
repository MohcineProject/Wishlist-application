<?php
// Created by Mohcine Zahdi and Othmane Mounouar
namespace App\Entity;

use App\Repository\WishlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishlistRepository::class)]
class Wishlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column]
    private ?bool $isDisabled = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'wishlist', orphanRemoval: true, cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]

    private Collection $items;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'wishlists' )]
    private Collection $authors ;

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(?User $author): void
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addToAuthorWishlists($this);
        }
    }


    public function removeAuthor(?User $author): void {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
            $author->removeWishlist($this) ;
        }
    }

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function isDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(bool $isDisabled): static
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setWishlist($this);
        }

        return $this;
    }

    public function deleteItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getWishlist() === $this) {
                $item->setWishlist(null);
            }
        }

        return $this;
    }


    public function sortItemByPrice() {


        $itemsArray = (($this->items->toArray()))  ; 

        usort($itemsArray , function(Item $a , Item $b ){
            return $a->getPrice() - $b->getPrice() ;
        } ) ;

        return $itemsArray ; 
    }



    public function getItemById(int $id){
        $itemsArray = (($this->items->toArray()))  ; 
        for ($i = 0; $i < sizeof($itemsArray); $i++) {
            $item = $itemsArray[$i];
            if ($item->getId() == $id ) {
                return $item;
            }
        }
        return null ;
    }


    public function wishlistTotalPrice(): float
    {
        $itemsArray = $this->items->toArray(); // Pas besoin des doubles parenthèses
        $total = 0;
    
        for ($i = 0; $i < count($itemsArray); $i++) {
            $total += $itemsArray[$i]->getPrice(); // Utilisation correcte des crochets
        }
    
        return $total;
    }
}
