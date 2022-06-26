<?php

namespace App\Tests;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTest extends WebTestCase
{
    use ArraySubsetAsserts;

    protected KernelBrowser $client;
    protected Generator     $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker  = Factory::create();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
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