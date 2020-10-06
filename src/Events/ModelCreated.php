<?php

namespace Lerouse\LaravelRepository\Events;

use Illuminate\Database\Eloquent\Model;

class ModelCreated
{
    /** @var Model */
    public $model;

    /**
     * Repository Model Created Event
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}