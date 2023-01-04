<?php

namespace App\Tests;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Doctrine\Bundle\DoctrineBundle\Registry;
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

    protected KernelBrowser          $client;
    protected Application            $app;
    protected Generator              $faker;
    protected Registry $entityManager;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker  = Factory::create();

        $this->client = self::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine');
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
}
