<?php

namespace Lerouse\LaravelRepository\Tests\Fixtures\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Lerouse\LaravelRepository\EloquentRepository;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\TestModel;

class TestModelRepository extends EloquentRepository
{

    /**
     * Get the Repository Query Builder
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return TestModel::query();
    }

}