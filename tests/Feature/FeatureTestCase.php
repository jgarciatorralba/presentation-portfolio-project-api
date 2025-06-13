<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Tests\Feature\Trait\CanAccessLogs;
use App\Tests\Feature\Trait\CanConnectToDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

abstract class FeatureTestCase extends WebTestCase
{
    use CanAccessLogs;
    use CanConnectToDatabase;

    protected ?HttpKernelBrowser $client = null;

    /**
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws \TypeError
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

		/** @var EntityManagerInterface|null $entityManager */
		$entityManager = $this->getContainer()
			->get(EntityManagerInterface::class);

        $this->entityManager = $entityManager instanceof EntityManagerInterface
			? $entityManager
			: null;
    }

    protected function tearDown(): void
    {
        $this->client = null;
        $this->entityManager = null;

        parent::tearDown();
    }
}
