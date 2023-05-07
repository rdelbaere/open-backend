<?php

namespace App\Service;

use App\Entity\Tempfile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TempfileService
{
    private array $config;

    public function __construct(
        private EntityManagerInterface $em,
        ParameterBagInterface $parameterBag
    ) {
        $this->config = $parameterBag->get('tempfile');
    }

    public function preload(UploadedFile $file, User $user): Tempfile
    {
        $tempfile = new Tempfile();
        $tempfile->setFilename($this->generateName());
        $tempfile->setFiletype($file->guessExtension());
        $tempfile->setUser($user);

        $file->move($this->config['basePath'], $tempfile->getFilename());

        $this->em->persist($tempfile);
        $this->em->flush();
        return $tempfile;
    }

    public function buildPath(Tempfile $tempfile): string
    {
        return sprintf('%s/%s', $this->config['basePath'], $tempfile->getFilename());
    }

    public function consumed(Tempfile $tempfile): void
    {
        $this->em->remove($tempfile);
        $this->em->flush();
    }

    private function generateName(): string
    {
        return uniqid('tmp_');
    }
}
