<?php

namespace Lerouse\LaravelRepository\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Lerouse\LaravelRepository\Repository\RepositoryNotFoundException;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\FailedModel;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\TestModel;
use Lerouse\LaravelRepository\Tests\Fixtures\Repositories\TestModelRepository;
use Lerouse\LaravelRepository\Tests\LaravelTestCase;

class HasRepositoryTest extends LaravelTestCase
{
    use RefreshDatabase;

    /** @test **/
    public function can_get_models_repository(): void
    {
        $model = new TestModel;

        $repository = $model->getRepository();

        self::assertInstanceOf(TestModelRepository::class, $repository);
    }

    /** @test **/
    public function can_get_model_repository_namespace(): void
    {
        $model = new TestModel;

        self::assertEquals('Lerouse\LaravelRepository\Tests\Fixtures\Repositories', $model->getRepositoryNamespace());
    }

    /** @test **/
    public function can_get_route_model_binding_via_repository(): void
    {
        $testModel = factory(TestModel::class)->create();

        $model = new TestModel;

        self::assertEquals(
            $testModel->toArray(),
            $model->resolveRouteBinding($testModel->getKey())->toArray()
        );
    }

    /** @test **/
    public function route_model_binding_via_repository_returns_null_if_no_model_available(): void
    {
        $model = new TestModel;

        self::assertNull($model->resolveRouteBinding(1));
    }

    /** @test **/
    public function model_missing_repository_throws_exception(): void
    {
        $this->expectException(RepositoryNotFoundException::class);
        $this->expectExceptionMessage('Model Repository \'Lerouse\LaravelRepository\Tests\Fixtures\Repositories\FailedModelRepository\' not found.');

        $model = new FailedModel;

        $model->getRepository();
    }

    /** @test **/
    public function route_model_binding_throws_exception_if_no_repository_available(): void
    {

        $testModel = factory(FailedModel::class)->create();

        $model = new FailedModel;

        self::assertEquals(
            $testModel->toArray(),
            $model->resolveRouteBinding($testModel->getKey())->toArray()
        );
    }

}