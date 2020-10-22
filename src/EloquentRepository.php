<?php

namespace Lerouse\LaravelRepository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Lerouse\LaravelRepository\Events\ManyModelsCreated;
use Lerouse\LaravelRepository\Events\ManyModelsDeleted;
use Lerouse\LaravelRepository\Events\ManyModelsUpdated;
use Lerouse\LaravelRepository\Events\ModelCreated;
use Lerouse\LaravelRepository\Events\ModelDeleted;
use Lerouse\LaravelRepository\Events\ModelUpdated;

abstract class EloquentRepository implements RepositoryInterface
{
    /** @var string */
    protected $key = 'id';

    /** @var int */
    protected $paginationLimit = 25;

    /**
     * Get all Models
     *
     * @return EloquentCollection
     */
    public function all(): EloquentCollection
    {
        return $this->builder()->get();
    }

    /**
     * Get all models as Paginated Results
     *
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function paginate(int $limit = null): LengthAwarePaginator
    {
        return $this->builder()->paginate($limit ?? $this->paginationLimit);
    }

    /**
     * Find a Model by its Primary Key
     *
     * @param mixed $id
     * @param bool $fail
     * @return mixed
     */
    public function find($id, bool $fail = true)
    {
        if ($fail) {
            return $this->builder()->findOrFail($id);
        }

        return $this->builder()->find($id);
    }

    /**
     * Find Many models by its primary Key
     *
     * @param array $ids
     * @param string|null $column
     * @return Collection
     */
    public function findMany(array $ids, string $column = null): Collection
    {
        $column = $column ?? $this->getModelPrimaryKey();

        return $this->builder()->whereIn($this->getModelTable() . '.' . $column, $ids)->get();
    }

    /**
     * Create a new Model
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $model = $this->builder()->create($data);

        Event::dispatch(new ModelCreated($model));

        return $model;
    }

    /**
     * Create a collection of new Models
     *
     * @param Collection $collection
     * @return bool
     */
    public function createMany(Collection $collection): bool
    {
        $result = $this->builder()->getModel()->insert($collection->toArray());

        Event::dispatch(new ManyModelsCreated(
            get_class($this->builder()->getModel())
        ));

        return $result;
    }

    /**
     * Update a Model by its Primary Key
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        $model = $this->find($id);

        $model->update($data);

        $model = $model->fresh();

        Event::dispatch(new ModelUpdated($model));

        return $model;
    }

    /**
     * Update a collection of Models
     *
     * @param array $data
     * @param array $ids
     * @param string|null $column
     * @return bool
     */
    public function updateMany(array $data, array $ids, string $column = null): bool
    {
        $column = $column ?? $this->getModelPrimaryKey();

        $result = $this->builder()->whereIn($this->getModelTable() . '.' . $column, $ids)->update($data);

        Event::dispatch(new ManyModelsUpdated(
            get_class($this->builder()->getModel())
        ));

        return $result;
    }

    /**
     * Delete a Model by its Primary Key
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $model = $this->find($id);

        $model->delete();

        Event::dispatch(new ModelDeleted($model));

        return $model->fresh();
    }

    /**
     * Delete a collection of Models by their Primary Keys
     *
     * @param array $ids
     * @param string|null $column
     * @return mixed
     */
    public function deleteMany(array $ids, string $column = null): void
    {
        $column = $column ?? $this->getModelPrimaryKey();

        $this->builder()->whereIn($this->getModelTable() . '.' . $column, $ids)->delete();

        Event::dispatch(new ManyModelsDeleted(
            get_class($this->builder()->getModel())
        ));
    }

    /**
     * Get the next Auto Increment on table
     *
     * @param string|null $column
     * @return int
     */
    public function getNextAutoIncrement(string $column = null): int
    {
        $column = $column ?? $this->getModelPrimaryKey();

        $latest = $this->builder()->orderBy($this->getModelTable() . '.' . $column, 'desc')->first();

        return $latest ? ($latest->getKey() + 1) : 1;
    }

    /**
     * Get the Models Primary Key
     *
     * @return string|null
     */
    protected function getModelPrimaryKey(): string
    {
        return $this->builder()->getModel()->getKey() ?? $this->key;
    }

    /**
     * Get the Models Table
     *
     * @return string
     */
    protected function getModelTable(): string
    {
        return $this->builder()->getModel()->getTable();
    }

    /**
     * Get the Repository Query Builder
     *
     * @return Builder
     */
    abstract public function builder(): Builder;

}