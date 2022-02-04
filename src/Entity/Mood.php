<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MoodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MoodRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'get',
        'delete' => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
    denormalizationContext: ['groups' => ['mood:write']],
    normalizationContext: ['groups' => ['mood:read']],
)]
class Mood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["mood:read", "mood:write", "feel:read"])]
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
