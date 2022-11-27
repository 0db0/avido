<?php

namespace App\Tests;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Faker\Factory;
use Faker\Generator;
use Hautelook\AliceBundle\PhpUnit\RefreshTestTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;

abstract class AbstractWebTest extends WebTestCase
{
//    use RefreshTestTrait;
    use ArraySubsetAsserts;

    protected KernelBrowser $client;
    protected Application   $app;
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
