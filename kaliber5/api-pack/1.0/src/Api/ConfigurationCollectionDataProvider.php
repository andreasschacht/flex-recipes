<?php

namespace App\Api;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Configuration;

/**
 * Class ConfigurationCollectionDataProvider.
 */
class ConfigurationCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @param string      $resourceClass
     * @param string|null $operationName
     *
     * @return array
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        // always return an empty collection
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Configuration::class === $resourceClass;
    }
}
