<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Filesystem;
use App\Service\FilesystemService;

class FilesystemProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $itemProvider,
        private FilesystemService $filesystemService,
    ){}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Filesystem
    {
        $filesystem = $this->itemProvider->provide($operation, $uriVariables, $context);

        $rootDirectory = $this->filesystemService->explore($filesystem);
        $filesystem->setRootDirectory($rootDirectory);

        return $filesystem;
    }
}
