<?php

namespace Lerouse\LaravelRepository\Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Lerouse\LaravelRepository\EloquentRepository;
use Lerouse\LaravelRepository\RepositoryInterface;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\TestModel;
use Lerouse\LaravelRepository\Tests\Fixtures\Repositories\TestModelRepository;
use Lerouse\LaravelRepository\Tests\LaravelTestCase;

class EloquentRepositoryTest extends LaravelTestCase
{
    /** @var RepositoryInterface */
    protected $repository;

    /** {@inheritdoc} **/
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TestModelRepository;
    }

    /** @test **/
    public function can_create_an_eloquent_repository(): void
    {
        self::assertInstanceOf(EloquentRepository::class, $this->repository);
    }

    /** @test **/
    public function can_retrieve_all(): void
    {
        $collection = factory(TestModel::class, 2)->create();

        self::assertEquals($collection->toArray(), $this->repository->all()->toArray());
    }


    /** @test **/
    public function can_find(): void
    {
        $model = factory(TestModel::class)->create();

        self::assertEquals($model->toArray(), $this->repository->find($model->getKey())->toArray());
    }

    /** @test **/
    public function can_create(): void
    {
        $this->repository->create([
            'value' => 'Create Method'
        ]);

        $this->assertDatabaseHas('test_models', [
            'id' => 1,
            'value' => 'Create Method'
        ]);
    }

    /** @test **/
    public function can_create_many(): void
    {
        $collection = collect([
            ['value' => 'Model 1'],
            ['value' => 'Model 2']
        ]);

        $this->repository->createMany($collection);

        $this->assertDatabaseHas('test_models', [
            'id' => 1,
            'value' => 'Model 1'
        ]);

        $this->assertDatabaseHas('test_models', [
            'id' => 2,
            'value' => 'Model 2'
        ]);
    }

    /** @test **/
    public function can_update(): void
    {
        $model = factory(TestModel::class)->create();

        $this->repository->update([
            'value' => 'Updated Method'
        ], $model->getKey());

        $this->assertDatabaseHas('test_models', [
            'id' => 1,
            'value' => 'Updated Method'
        ]);
    }

    /** @test **/
    public function can_update_many(): void
    {
        $collection = factory(TestModel::class, 2)->create();

        $this->repository->updateMany([
            'value' => 'Update all Models'
        ], $collection->pluck('id')->toArray());

        $this->assertDatabaseHas('test_models', [
            'id' => 1,
            'value' => 'Update all Models'
        ]);

        $this->assertDatabaseHas('test_models', [
            'id' => 2,
            'value' => 'Update all Models'
        ]);
    }

    /** @test **/
    public function can_delete(): void
    {
        $model = factory(TestModel::class)->create();

        $this->repository->delete($model->getKey());

        $this->assertDatabaseMissing('test_models', [
            'id' => 1
        ]);
    }

    /** @test **/
    public function can_delete_many(): void
    {
        $collection = factory(TestModel::class, 2)->create();

        $this->repository->deleteMany($collection->pluck('id')->toArray());

        $this->assertDatabaseMissing('test_models', [
            'id' => 1
        ]);

        $this->assertDatabaseMissing('test_models', [
            'id' => 2
        ]);
    }

    /** @test **/
    public function can_get_next_auto_increment(): void
    {
        factory(TestModel::class, 2)->create();

        self::assertEquals(3, $this->repository->getNextAutoIncrement());
    }

    /** @test **/
    public function get_next_auto_increment_on_empty_table_returns_one(): void
    {
        self::assertEquals(1, $this->repository->getNextAutoIncrement());
    }

    /** @test **/
    public function can_get_models_primary_key(): void
    {
        $reflection = new \ReflectionClass(TestModelRepository::class);

        $method = $reflection->getMethod('getModelPrimaryKey');

        $method->setAccessible(true);

        self::assertEquals('id', $method->invokeArgs($this->repository, []));
    }

    /** @test **/
    public function can_get_models_table(): void
    {
        $reflection = new \ReflectionClass(TestModelRepository::class);

        $method = $reflection->getMethod('getModelTable');

        $method->setAccessible(true);

        self::assertEquals('test_models', $method->invokeArgs($this->repository, []));
    }

    /** @test **/
    public function can_get_builder(): void
    {
        self::assertInstanceOf(Builder::class, $this->repository->builder());
    }

}