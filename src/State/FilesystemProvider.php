<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\FilesystemService;

class FilesystemProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $itemProvider,
        private FilesystemService $filesystemService,
    ){}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $filesystem = $this->itemProvider->provide($operation, $uriVariables, $context);
        $this->filesystemService->explore($filesystem);
        return $filesystem;
    }
}
