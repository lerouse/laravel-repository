<?php

namespace Lerouse\LaravelRepository\Events;

use Illuminate\Database\Eloquent\Model;

class ModelUpdated
{
    /** @var Model */
    public $model;

    /**
     * Repository Model Updated Event
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}