<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\App\AppInstallController;
use App\Controller\App\AppUninstallController;
use App\Repository\AppRepository;
use App\State\AppProvider;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(provider: AppProvider::class),
        new Get(provider: AppProvider::class),
        new Post(uriTemplate: '/apps/{id}/install', controller: AppInstallController::class),
        new Post(uriTemplate: '/apps/{id}/uninstall', controller: AppUninstallController::class)
    ], order: ['name' => 'ASC']
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
