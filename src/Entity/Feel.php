<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\GetFeelController;
use App\Controller\GetFeelRecapController;
use App\Controller\PostFeelController;
use App\Repository\FeelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FeelRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            "method" => "GET",
            "path" => "/feels",
            "controller" => GetFeelController::class,
        ],
        'get_recap' => [
            "method" => "GET",
            "path" => "/feels/recap/{scale}",
            "controller" => GetFeelRecapController::class,
            "swagger_context" => [
                "parameters" => [
                    [
                        "name" => "scale",
                        "in" => "path",
                        "type" => "string",
                        "required" => true,
                    ],
                ],
            ],
        ],
        'post'=> [
            "method" => "POST",
            "path" => "/feels",
            "controller" => PostFeelController::class,
        ]
    ],
    itemOperations: [
        'get',
        'put' => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
        'delete' => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
    denormalizationContext: ['groups' => ['feel:write']],
    normalizationContext: ['groups' => ['feel:read']],
)]
class Feel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["feel:read"])]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Groups(["feel:read", "feel:write", "user:read"])]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["feel:read", "feel:write", "user:read"])]
    private $note;

    #[ORM\ManyToOne(targetEntity: Mood::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["feel:read", "feel:write", "user:read"])]
    private $mood;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'feels')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["feel:write"])]
    private $owner;

    #[ORM\Column(type: 'date')]
    #[Groups(["feel:read"])]
    private $date;


    public function __construct()
    {
        // $this->owner = TokenStorageInterface::getToken()->getUser();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getMood(): ?Mood
    {
        return $this->mood;
    }

    public function setMood(?Mood $mood): self
    {
        $this->mood = $mood;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
