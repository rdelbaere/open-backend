<?php

namespace App\Controller\Common;

use App\Service\TempfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tempfile', name: 'common_tempfile_')]
class TempfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TempfileService $tempfileService
    ) {}

    #[Route('', name: 'preload', methods: ['POST'])]
    public function preload(Request $request): Response
    {
        $uploadedFile = $request->files->get('file');
        $tempfile = $this->tempfileService->preload($uploadedFile, $this->getUser());

        $this->em->persist($tempfile);
        $this->em->flush();

        return $this->json([
            'status' => true,
            'data' => $tempfile
        ], Response::HTTP_OK, [], [
            'groups' => ['tempfile:read']
        ]);
    }
}
