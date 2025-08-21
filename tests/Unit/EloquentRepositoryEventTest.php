<?php

namespace Lerouse\LaravelRepository\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Lerouse\LaravelRepository\Events\ManyModelsCreated;
use Lerouse\LaravelRepository\Events\ManyModelsDeleted;
use Lerouse\LaravelRepository\Events\ManyModelsUpdated;
use Lerouse\LaravelRepository\Events\ModelCreated;
use Lerouse\LaravelRepository\Events\ModelDeleted;
use Lerouse\LaravelRepository\Events\ModelUpdated;
use Lerouse\LaravelRepository\RepositoryInterface;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\TestModel;
use Lerouse\LaravelRepository\Tests\Fixtures\Repositories\TestModelRepository;
use Lerouse\LaravelRepository\Tests\LaravelTestCase;
use PHPUnit\Framework\Attributes\Test;

class EloquentRepositoryEventTest extends LaravelTestCase
{
    use RefreshDatabase;

    /** @var RepositoryInterface */
    protected $repository;

    /** {@inheritdoc} **/
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TestModelRepository;

        Event::fake();
    }

    #[Test]
    public function creating_a_model_fires_event(): void
    {
        $model = $this->repository->create(['value' => 'Test Model']);

        Event::assertDispatched(ModelCreated::class, function ($event) use ($model) {
            return $event->model->getKey() === $model->getKey();
        });
    }

    #[Test]
    public function updating_a_model_fires_event(): void
    {
        $model = factory(TestModel::class)->create();

        $this->repository->update(['value' => 'Test Model'], $model->getKey());

        Event::assertDispatched(ModelUpdated::class, function ($event) use ($model) {
            return $event->model->getKey() === $model->getKey();
        });
    }

    #[Test]
    public function deleting_a_model_fires_event(): void
    {
        $model = factory(TestModel::class)->create();

        $this->repository->delete($model->getKey());

        Event::assertDispatched(ModelDeleted::class, function ($event) use ($model) {
            return $event->model->getKey() === $model->getKey();
        });
    }

    #[Test]
    public function creating_many_models_fires_event(): void
    {
        $this->repository->createMany([
            ['value' => 'Model 1'],
            ['value' => 'Model 2']
        ]);

        Event::assertDispatched(ManyModelsCreated::class, function ($event) {
            return $event->model === TestModel::class;
        });
    }

    #[Test]
    public function updating_many_models_fires_event(): void
    {
        $collection = factory(TestModel::class, 2)->create();

        $this->repository->updateMany([
            'value' => 'Update all Models'
        ], $collection->pluck('id')->toArray());

        Event::assertDispatched(ManyModelsUpdated::class, function ($event) {
            return $event->model === TestModel::class;
        });
    }

    #[Test]
    public function deleting_many_models_fires_event(): void
    {
        $collection = factory(TestModel::class, 2)->create();

        $this->repository->deleteMany($collection->pluck('id')->toArray());

        Event::assertDispatched(ManyModelsDeleted::class, function ($event) {
            return $event->model === TestModel::class;
        });
    }

}
