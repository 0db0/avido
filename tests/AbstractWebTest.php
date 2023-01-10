<?php

namespace App\Tests;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    use ArraySubsetAsserts;

    protected KernelBrowser       $client;
    protected Application         $app;
    protected Generator           $faker;
    protected EntityManager|null  $em;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker  = Factory::create();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
    }

    protected function generateUrl(string $routeName, array $params = []): string
    {
        return $this->client->getContainer()
            ->get('router')
            ->generate($routeName, $params);
    }

    protected function faker(): Generator
    {
        return $this->faker;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->em->close();
        $this->em = null;
    }
}
