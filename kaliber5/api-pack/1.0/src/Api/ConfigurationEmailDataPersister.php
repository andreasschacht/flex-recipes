<?php

namespace App\Api;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\ConfigurationEmail;

/**
 * Class ConfigurationEmailDataPersister.
 */
class ConfigurationEmailDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof ConfigurationEmail;
    }

    /**
     * @param ConfigurationEmail $data
     * @param array              $context
     *
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        $data->setId((int) uniqid(time()));

        return $data;
    }

    /**
     * @param       $data
     * @param array $context
     */
    public function remove($data, array $context = [])
    {
    }
}
