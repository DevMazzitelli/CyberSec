<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?int $total = null;

    // Relation User & Adresse
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Address::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private $address;

    #[ORM\Column]
    private ?\DateTimeImmutable $ending_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        $this->setEndingAt($created_at->modify('+30 days')); // Mettre à jour ending_at automatiquement

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getEndingAt(): ?\DateTimeImmutable
    {
        return $this->ending_at;
    }

    public function setEndingAt(\DateTimeImmutable $ending_at): static
    {
        $this->ending_at = $ending_at;

        return $this;
    }

    // Méthode pour obtenir le prix en fonction de l'abonnement de l'utilisateur
    public function getPrice(): float
    {
        $subscriptionType = $this->user->getIsSub();
        switch ($subscriptionType) {
            case 1: // Abonnement essentiel
                return 6.99;
            case 2: // Abonnement avancé
                return 14.99;
            case 3: // Abonnement prestige
                return 29.99;
            default:
                return 0; // Par défaut, pas d'abonnement
        }
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
