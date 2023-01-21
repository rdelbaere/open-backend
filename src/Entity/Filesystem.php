<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FileSystemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FileSystemRepository::class)]
#[ApiResource]
class Filesystem
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['system:read'])]
    private Uuid $id;

    #[ORM\OneToOne(mappedBy: 'fileSystem', cascade: ['persist', 'remove'])]
    private ?System $system = null;


    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSystem(): ?System
    {
        return $this->system;
    }

    public function setSystem(System $system): self
    {
        if ($system->getFileSystem() !== $this) {
            $system->setFileSystem($this);
        }

        $this->system = $system;

        return $this;
    }
}
