<?php

namespace App\Controller\Filesystem;

use App\Entity\Filesystem;
use App\Service\FilesystemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/filesystems/{id}/resource', 'filesystem_resource_')]
class ResourceController extends AbstractController
{
    public function __construct(private FilesystemService $filesystemService) {}

    #[Route('', 'post', methods: ['POST'])]
    public function post(Request $request, Filesystem $filesystem): Response
    {
        $resource = $this->filesystemService->deserializeResource($request->getContent());
        $this->filesystemService->createResource($filesystem, $resource);

        $rootDirectory = $this->filesystemService->explore($filesystem);
        $filesystem->setRootDirectory($rootDirectory);

        return $this->json([
            'status' => true,
            'data' => $filesystem,
        ], Response::HTTP_OK, [], [
            'groups' => ['filesystem:read'],
        ]);
    }
}
