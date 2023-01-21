<?php

namespace App\Event;

use App\Entity\Filesystem;
use Symfony\Component\Filesystem\Filesystem as FilesystemManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FilesystemAutomation
{
    private array $config;

    public function __construct(private FilesystemManager $manager, ParameterBagInterface $parameterBag)
    {
        $this->config = $parameterBag->get('filesystem');
    }

    public function postPersist(Filesystem $filesystem):  void
    {
        $path = sprintf('%s/%s', $this->config['basePath'], $filesystem->getId());
        $this->manager->mkdir($path);
    }
}
