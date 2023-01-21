<?php

namespace App\Controller\App;

use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class AppInstallController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private Security $security) {}

    public function __invoke(App $data): App
    {
        $system = $this->security->getUser()->getSystem();
        $system->addApp($data);
        $this->em->flush();

        $data->setInstalled(true);
        return $data;
    }
}
