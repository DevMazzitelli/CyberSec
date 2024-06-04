<?php

// src/Entity/User.php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Peut être que cette adresse existe déjà ? Essayer une autre adresse.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column]
    private ?int $isSub = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $StripeSubscriptionId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $is_sub_time_end = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $is_sub_time_end_2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $is_sub_time_end_3 = null;

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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear temporary, sensitive data here
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getIsSub(): ?int
    {
        return $this->isSub;
    }

    public function setIsSub(int $isSub): static
    {
        $this->isSub = $isSub;

        return $this;
    }

    public function getStripeSubscriptionId(): ?string
    {
        return $this->StripeSubscriptionId;
    }

    public function setStripeSubscriptionId(?string $StripeSubscriptionId): static
    {
        $this->StripeSubscriptionId = $StripeSubscriptionId;

        return $this;
    }

    public function getIsSubTimeEnd(): ?\DateTimeInterface
    {
        return $this->is_sub_time_end;
    }

    public function setIsSubTimeEnd(?\DateTimeInterface $is_sub_time_end): static
    {
        $this->is_sub_time_end = $is_sub_time_end;

        return $this;
    }

    public function getIsSubTimeEnd2(): ?\DateTimeInterface
    {
        return $this->is_sub_time_end_2;
    }

    public function setIsSubTimeEnd2(?\DateTimeInterface $is_sub_time_end_2): static
    {
        $this->is_sub_time_end_2 = $is_sub_time_end_2;

        return $this;
    }

    public function getIsSubTimeEnd3(): ?\DateTimeInterface
    {
        return $this->is_sub_time_end_3;
    }

    public function setIsSubTimeEnd3(?\DateTimeInterface $is_sub_time_end_3): static
    {
        $this->is_sub_time_end_3 = $is_sub_time_end_3;

        return $this;
    }

}
