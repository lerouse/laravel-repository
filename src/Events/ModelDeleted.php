<?php

namespace Lerouse\LaravelRepository\Events;

use Illuminate\Database\Eloquent\Model;

class ModelDeleted
{
    /** @var Model */
    public $model;

    /**
     * Repository Model Deleted Event
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}