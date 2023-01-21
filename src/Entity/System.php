<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Model\Configuration;
use App\Repository\SystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SystemRepository::class)]
#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get' => [
            'security' => 'is_granted("SYSTEM_READ", object)'
        ],
        'patch' => [
            'security' => 'is_granted("SYSTEM_WRITE", object)'
        ]
    ],
    denormalizationContext: ['groups' => ['system:write']],
    normalizationContext: ['groups' => ['system:read']],
)]
class System
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['system:read', 'user:read'])]
    private int $id;

    #[ORM\OneToOne(inversedBy: 'system', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'json_document')]
    #[Groups(['system:read', 'system:write'])]
    private Configuration $configuration;

    #[ORM\ManyToMany(targetEntity: App::class)]
    private Collection $apps;

    public function __construct()
    {
        $this->apps = new ArrayCollection();
        $this->configuration = new Configuration();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConfiguration(): ?Configuration
    {
        return $this->configuration;
    }

    public function setConfiguration(Configuration $configuration): self
    {
        $this->configuration = clone($configuration);

        return $this;
    }

    public function getApps(): Collection
    {
        return $this->apps;
    }

    public function addApp(App $app): self
    {
        if (!$this->apps->contains($app)) {
            $this->apps[] = $app;
        }

        return $this;
    }

    public function removeApp(App $app): self
    {
        $this->apps->removeElement($app);

        return $this;
    }
}
