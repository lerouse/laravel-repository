<?php

namespace Lerouse\LaravelRepository\Tests\Unit;

use Lerouse\LaravelRepository\Repository\RepositoryNotFoundException;
use Lerouse\LaravelRepository\Support\UsesRepositoryManager;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\TestModel;
use Lerouse\LaravelRepository\Tests\Fixtures\Repositories\TestModelRepository;
use Lerouse\LaravelRepository\Tests\LaravelTestCase;
use PHPUnit\Framework\Attributes\Test;

class UsesRepositoryManagerTest extends LaravelTestCase
{
    use UsesRepositoryManager;

    #[Test]
    public function can_load_a_repository(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepository('TestModel'));
    }

    #[Test]
    public function can_load_repository_with_appending_text(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepository('TestModelRepository'));
    }

    #[Test]
    public function exception_thrown_if_repository_does_not_exist(): void
    {
        $this->expectException(RepositoryNotFoundException::class);

       $this->getRepository('FakeRepository');
    }

    #[Test]
    public function can_load_repository_using_fqdn(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepository(TestModel::class));
    }

    #[Test]
    public function can_load_repository_using_repositories_fqdn(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepositoryManager()->getRepository(TestModelRepository::class));
    }

    #[Test]
    public function exception_thrown_if_repository_fqdn_does_not_exist(): void
    {
        $this->expectException(RepositoryNotFoundException::class);

        $this->getRepository(\App\Models\Fake::class);
    }

}
