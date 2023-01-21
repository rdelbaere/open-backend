<?php

namespace App\Event;

use App\Entity\Filesystem;
use App\Service\FilesystemService;

class FilesystemAutomation
{
    public function __construct(private FilesystemService $filesystemService) {}

    public function postPersist(Filesystem $filesystem):  void
    {
        $this->filesystemService->prepare($filesystem);
    }
}
