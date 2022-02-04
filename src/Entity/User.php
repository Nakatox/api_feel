<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserController;
use App\Filter\RecapFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            "method" => "GET",
            "path" => "/users/me",
            "controller" => UserController::class,
        ],
        'post',
    ],
    itemOperations: [
        'put' => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        'delete' => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"]
    ],
    denormalizationContext: ['groups' => ['user:write']],
    normalizationContext: ['groups' => ['user:read']],
)]
#[ApiFilter(RecapFilter::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["user:read", "user:write", "feel:read"])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(["user:write"])]
    private $password;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Feel::class)]
    #[Groups(["user:read", "user:write"])]
    private $feels;

    public function __construct()
    {
        $this->feels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Feel[]
     */
    public function getFeels(): Collection
    {
        return $this->feels;
    }

    public function addFeel(Feel $feel): self
    {
        if (!$this->feels->contains($feel)) {
            $this->feels[] = $feel;
            $feel->setOwner($this);
        }

        return $this;
    }

    public function removeFeel(Feel $feel): self
    {
        if ($this->feels->removeElement($feel)) {
            // set the owning side to null (unless already changed)
            if ($feel->getOwner() === $this) {
                $feel->setOwner(null);
            }
        }

        return $this;
    }
}
