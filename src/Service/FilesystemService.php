<?php

namespace App\Service;

use App\Entity\Filesystem;
use App\Model\Filesystem\Directory;
use App\Model\Filesystem\File;
use App\Model\Filesystem\Resource;
use App\Util\Exception\BackendFilesystemException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem as FilesystemManager;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FilesystemService
{
    private array $config;

    public function __construct(
        private DecoderInterface $decoder,
        private DenormalizerInterface $denormalizer,
        private FilesystemManager $manager,
        ParameterBagInterface $parameterBag,
        private TempfileService $tempfileService
    ){
        $this->config = $parameterBag->get('filesystem');
    }

    public function explore(Filesystem $filesystem): Directory
    {
        $path = $this->buildPath($filesystem);
        $rootDirectory = new Directory();
        $this->buildTree($path, $rootDirectory);
        return $rootDirectory;
    }

    public function prepare(Filesystem $filesystem): void
    {
        $path = $this->buildPath($filesystem);
        $this->manager->mkdir($path);
    }

    public function deserializeResource(string $json): Resource
    {
        $data = $this->decoder->decode($json, 'json');
        $type = $data['isDirectory'] ? Directory::class : File::class;

        return $this->denormalizer->denormalize($data, $type);
    }

    public function createResource(Filesystem $filesystem, Resource $resource): void
    {
        $path = sprintf('%s/%s/%s', $this->buildPath($filesystem), $resource->getPath(), $resource->getName());

        if ($this->manager->exists($path)) {
            throw new BackendFilesystemException(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: 'filesystem.resource_already_exist',
                path: $path,
            );
        }

        if ($resource instanceof Directory) {
            $this->manager->mkdir($path);
        } else if ($resource instanceof File) {
            $tempfilePath = $this->tempfileService->buildPath($resource->getTempfile());
            $path = sprintf('%s.%s', $path, $resource->getTempfile()->getFiletype());
            $this->manager->rename($tempfilePath, $path);
        }
    }

    private function buildPath(Filesystem $filesystem): string
    {
        return sprintf('%s/%s', $this->config['basePath'], $filesystem->getId());
    }

    private function buildTree(string $path, Directory $parent): void
    {
        $finder = new Finder();
        $finder->depth(0)->in($path);

        foreach ($finder as $entry) {
            $resource = $entry->isDir() ? new Directory() : new File();

            $resource->setName($entry->getFilename());
            $resource->setPath(sprintf('%s/%s', $parent->getPath(), $entry->getRelativePathname()));

            if ($resource instanceof Directory) {
                $this->buildTree($entry->getRealPath(), $resource);
            }

            $resource->setParent($parent);
            $parent->addChild($resource);
        }
    }
}
