<?php

namespace App\Api;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Configuration;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ConfigurationDataPersister.
 */
class ConfigurationDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var DataPersisterInterface
     */
    private $ormDataPersister;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * ConfigurationDataPersister constructor.
     *
     * @param DataPersisterInterface $ormDataPersister
     * @param ManagerRegistry        $managerRegistry
     */
    public function __construct(DataPersisterInterface $ormDataPersister, ManagerRegistry $managerRegistry)
    {
        $this->ormDataPersister = $ormDataPersister;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Configuration && $this->ormDataPersister->supports($data, $context);
    }

    /**
     * @param Configuration $data
     * @param array         $context
     *
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        $repo = $this->managerRegistry->getRepository(Configuration::class);
        do {
            $hash = substr(md5(microtime()), -7);
        } while (null !== $repo->findOneBy(['hash' => $hash]));
        $data->setHash($hash);

        return $this->ormDataPersister->persist($data, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        return $this->ormDataPersister->remove($data, $context);
    }
}
