<?php

namespace App\Service;

use App\Entity\Tempfile;
use App\Entity\User;
use App\Util\Exception\BackendException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TempfileService
{
    private array $config;

    public function __construct(
        private EntityManagerInterface $em,
        private Filesystem $filesystem,
        ParameterBagInterface $parameterBag,
        private Security $security,
        private ValidatorInterface $validator
    ) {
        $this->config = $parameterBag->get('tempfile');
    }

    public function preload(UploadedFile $file, User $user): Tempfile
    {
        $violations = $this->validator->validate($file, $this->getValidationConstraints());
        if (count($violations) > 0) {
            throw new BackendException(
                statusCode: Response::HTTP_BAD_REQUEST,
                message: 'tempfile.invalid_file',
            );
        }

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

    public function consumed(Tempfile $tempfile, string $destination): void
    {
        if ($this->security->getUser() !== $tempfile->getUser()) {
            throw new BackendException(
                statusCode: Response::HTTP_FORBIDDEN,
                message: 'tempfile.forbidden',
            );
        }

        $tempfilePath = $this->buildPath($tempfile);
        $this->filesystem->rename($tempfilePath, $destination);

        $this->em->remove($tempfile);
        $this->em->flush();
    }

    private function generateName(): string
    {
        return uniqid('tmp_');
    }

    private function getValidationConstraints(): array
    {
        return [
            new File(
                maxSize: $this->config['maxSize'],
                mimeTypes: $this->config['allowedMimeTypes']
            ),
        ];
    }
}
