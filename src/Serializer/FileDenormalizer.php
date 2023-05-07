<?php
namespace App\Serializer;

use App\Model\Filesystem\File;
use App\Repository\TempfileRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FileDenormalizer implements DenormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
        private TempfileRepository $tempfileRepository
    ) {}

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): File
    {
        $file = $this->normalizer->denormalize($data, $type, $format, $context);

        $tempfile = $this->tempfileRepository->find($data['tempfile']['id']);
        $file->setTempfile($tempfile);

        return $file;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $type === File::class;
    }
}
