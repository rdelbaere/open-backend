<?php

namespace App\Service;

use App\Entity\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem as FilesystemManager;
use Symfony\Component\Finder\Finder;

class FilesystemService
{
    private array $config;

    public function __construct(private FilesystemManager $manager, ParameterBagInterface $parameterBag){
        $this->config = $parameterBag->get('filesystem');
    }

    public function explore(Filesystem $filesystem): void
    {
        $path = $this->buildPath($filesystem);
        $finder = new Finder();
        $finder->in($path);
    }

    public function prepare(Filesystem $filesystem): void
    {
        $path = $this->buildPath($filesystem);
        $this->manager->mkdir($path);
    }

    private function buildPath(Filesystem $filesystem): string
    {
        return sprintf('%s/%s/', $this->config['basePath'], $filesystem->getId());
    }
}
