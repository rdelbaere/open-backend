<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\App;
use Symfony\Bundle\SecurityBundle\Security;

class AppProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $collectionProvider,
        private ProviderInterface $itemProvider,
        private Security $security
    ){}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof CollectionOperationInterface) {
            $app = $this->itemProvider->provide($operation, $uriVariables, $context);

            if ($app) {
                $this->populateInstalled($app);
            }

            return $app;
        }

        $apps = $this->collectionProvider->provide($operation, $uriVariables, $context);

        foreach ($apps as $app) {
            $this->populateInstalled($app);
        }

        return $apps;
    }

    private function populateInstalled(App $app): void
    {
        $user = $this->security->getUser();
        $installed = $app->isByDefault() || $user->getSystem()->getApps()->contains($app);
        $app->setInstalled($installed);
    }
}
