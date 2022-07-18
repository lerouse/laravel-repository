<?php

namespace Lerouse\LaravelRepository\Tests\Unit;

use Lerouse\LaravelRepository\Repository\RepositoryNotFoundException;
use Lerouse\LaravelRepository\Services\RepositoryManagerService;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\TestModel;
use Lerouse\LaravelRepository\Tests\Fixtures\Repositories\TestModelRepository;
use Lerouse\LaravelRepository\Tests\LaravelTestCase;

class RepositoryManagerServiceTest extends LaravelTestCase
{

    /** @test **/
    public function can_load_a_repository(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepositoryManager()->getRepository('TestModel'));
    }

    /** @test **/
    public function can_load_repository_with_appending_text(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepositoryManager()->getRepository('TestModelRepository'));
    }

    /** @test **/
    public function exception_thrown_if_repository_does_not_exist(): void
    {
        $this->expectException(RepositoryNotFoundException::class);

        $this->assertInstanceOf(TestModelRepository::class, $this->getRepositoryManager()->getRepository('FakeRepository'));
    }

    /** @test **/
    public function can_load_repository_using_fqdn(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepositoryManager()->getRepository(TestModel::class));
    }

    /** @test **/
    public function can_load_repository_using_repositories_fqdn(): void
    {
        $this->assertInstanceOf(TestModelRepository::class, $this->getRepositoryManager()->getRepository(TestModelRepository::class));
    }

    /** @test **/
    public function exception_thrown_if_repository_fqdn_does_not_exist(): void
    {
        $this->expectException(RepositoryNotFoundException::class);

        $this->getRepositoryManager()->getRepository(\App\Models\Fake::class);
    }

    protected function getRepositoryManager(): RepositoryManagerService
    {
        return app()->make(RepositoryManagerService::class);
    }

}