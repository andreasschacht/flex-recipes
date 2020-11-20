<?php
namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister;
use Hautelook\AliceBundle\FixtureLocatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DbContext
 */
class DbContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function createSchemaBeforeScenario()
    {
        $this->emptyDatabase();
    }

    /**
     * @Given there are :file in the database
     * @param $file
     */
    public function thereAreFixturesInTheDatabase($file)
    {
        $this->loadFile($file);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function loadFile($names)
    {
//        $this->emptyDatabase();
        if (!is_array($names)) {
            $names = [$names];
        }

        $this->loadFixtureFiles($names);
    }

    /**
     * {@inheritdoc}
     */
    public function emptyDatabase()
    {
        umask(0000);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getTestContainer()->get('doctrine.orm.default_entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();


        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }

    /**
     * @param array $names
     */
    private function loadFixtureFiles(array $names)
    {
        /** @var FixtureLocatorInterface $locator */
        $locator = $this->getTestContainer()->get('hautelook_alice.locator');
        $fixtures = $locator->locateFiles([], 'test');

        $files = array_filter($fixtures, function (string $filename) use ($names) {
            foreach ($names as $name) {
                if (strpos($filename, '/' . $name . '.yaml') !== false) {
                    return true;
                }
            }

            return false;
        });
        /** @var EntityManagerInterface $em */
        $em = $this->getTestContainer()->get('doctrine.orm.entity_manager');

        $persister = new ObjectManagerPersister($em);

        /** @var FixtureLocatorInterface $locator */
        $loader = $this->getTestContainer()->get('hautelook_alice.data_fixtures.append_loader');

        $loader = $loader->withPersister($persister);

        $loader->load($files, $this->getTestContainer()->getParameterBag()->all());

        $this->getTestContainer()->get('doctrine.orm.entity_manager')->clear();
    }

    /**
     * @return TestContainer|null
     */
    private function getTestContainer(): ?TestContainer
    {
        return $this->kernel->getContainer()->get('test.service_container');
    }
}