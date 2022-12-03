<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\App;
use Symfony\Component\Security\Core\Security;

class AppDataProvider implements ContextAwareCollectionDataProviderInterface, DenormalizedIdentifiersAwareItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private CollectionDataProviderInterface $collectionDataProvider,
        private ItemDataProviderInterface $itemDataProvider,
        private Security $security
    ){}

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === App::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $apps = $this->collectionDataProvider->getCollection($resourceClass, $operationName, $context);

        foreach ($apps as $app) {
            $this->populateInstalled($app);
        }

        return $apps;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        $app = $this->itemDataProvider->getItem($resourceClass, $id, $operationName, $context);

        if (!$app) {
            return null;
        }

        $this->populateInstalled($app);

        return $app;
    }

    private function populateInstalled(App $app): void
    {
        $user = $this->security->getUser();
        $installed = $app->isIsDefault() || $user->getSystem()->getApps()->contains($app);
        $app->setInstalled($installed);
    }
}
