<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Model\Configuration;
use App\Repository\SystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SystemRepository::class)]
#[ApiResource(
    operations: [
        new Post(denormalizationContext: ['groups' => ['system:init']]),
        new Get(security: 'is_granted("SYSTEM_READ", object)'),
        new Patch(security: 'is_granted("SYSTEM_WRITE", object)')
    ],
    normalizationContext: ['groups' => ['system:read']],
    denormalizationContext: ['groups' => ['system:write']]
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
    #[Assert\NotNull]
    #[Groups(['system:init'])]
    private User $user;

    #[ORM\Column(type: 'json_document')]
    #[Groups(['system:read', 'system:write'])]
    private Configuration $configuration;

    #[ORM\ManyToMany(targetEntity: App::class)]
    private Collection $apps;

    #[ORM\OneToOne(inversedBy: 'system', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['system:read'])]
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->apps = new ArrayCollection();
        $this->configuration = new Configuration();
        $this->filesystem = new Filesystem();
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
        $this->configuration = clone $configuration;

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

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function setFilesystem(Filesystem $filesystem): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }
}
