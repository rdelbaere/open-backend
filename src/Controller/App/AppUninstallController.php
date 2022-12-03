<?php

namespace App\Controller\App;

use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class AppUninstallController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private Security $security) {}

    public function __invoke(App $data): App
    {
        $system = $this->security->getUser()->getSystem();
        $system->removeApp($data);
        $this->em->flush();

        $data->setInstalled(false);
        return $data;
    }
}
