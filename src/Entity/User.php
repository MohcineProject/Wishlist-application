<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private array $roles = [];

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 63)]
    private ?string $firstName = null;

    #[ORM\Column(length: 63)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isLocked = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $isAdmin = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Wishlist::class)]
    private Collection $wishlists;

    // #[ORM\OneToMany(mappedBy: 'invitedUser', targetEntity: Item::class)]
    // private Collection $invitations;


    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: PurchaseProof::class, cascade: ['persist', 'remove'])]
    private Collection $purchaseProofs;
    /**
     * @var Collection<int, Invitation>
     */
    #[ORM\ManyToMany(targetEntity: Invitation::class)]
    private Collection $invitations;





    public function __construct()
    {
        $this->wishlists = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
        $this->isLocked = false;
        $this->invitations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isLocked(): ?bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): static
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addToAuthorWhishlists(Wishlist $wishlist){
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists[] = $wishlist;
        }       
    }
    // public function getInvitations(): Collection
    // {
    //     return $this->invitations;
    // }

    // public function addInvitation(Item $invitation): static
    // {
    //     if (!$this->invitations->contains($invitation)) {
    //         $this->invitations[] = $invitation;
    //         $invitation->setInvitedUser($this);
    //     }

    // }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        $this->invitations->removeElement($invitation);

        return $this;
    }

    public function acceptInvitation(int $invitation_id) {
        for ($i =  0 ; $i < sizeof($this->invitations) ; $i++ ){
            if ($this->invitations[$i]->getId() == $invitation_id) {
                $wishlist = $this->invitations[$i]->getWishlist() ;
                unset($this->invitations[$i]) ;
                $this->addToAuthorWhishlists($wishlist);
            }
        }
    }


}
