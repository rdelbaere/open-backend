<?php

namespace App\Model\Filesystem;

use Symfony\Component\Serializer\Annotation\Groups;

abstract class Resource
{
    #[Groups(['filesystem:read'])]
    private string $name;

    #[Groups(['filesystem:read'])]
    private string $path;

    private ?Resource $parent;

    public function getParent(): ?Resource
    {
        return $this->parent;
    }

    public function setParent(?Resource $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
