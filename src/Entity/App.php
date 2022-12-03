<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AppRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
)]
class App
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 40)]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $icon = [];

    #[ORM\Column(type: 'string', length: 40)]
    private string $runtime;

    #[ORM\Column(type: 'boolean')]
    private bool $byDefault = false;

    private bool $installed;

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

    public function getIcon(): ?array
    {
        return $this->icon;
    }

    public function setIcon(array $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getRuntime(): ?string
    {
        return $this->runtime;
    }

    public function setRuntime(string $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function isByDefault(): ?bool
    {
        return $this->byDefault;
    }

    public function setByDefault(bool $byDefault): self
    {
        $this->byDefault = $byDefault;

        return $this;
    }

    public function isInstalled(): bool
    {
        return $this->installed;
    }

    public function setInstalled(bool $installed): void
    {
        $this->installed = $installed;
    }
}
