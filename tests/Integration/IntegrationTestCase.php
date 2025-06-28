<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Trait\CanConnectToDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

abstract class IntegrationTestCase extends KernelTestCase
{
    use CanConnectToDatabase;

    /**
     * @throws ServiceCircularReferenceException
     * @throws ServiceNotFoundException
     * @throws \TypeError
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var EntityManagerInterface|null $entityManager */
        $entityManager = $this->getContainer()
            ->get(EntityManagerInterface::class);

        $this->entityManager = $entityManager instanceof EntityManagerInterface
            ? $entityManager
            : null;
    }

    protected function tearDown(): void
    {
        $this->entityManager = null;

        parent::tearDown();
    }
}
