<?php

namespace Lerouse\LaravelRepository\Events;

class ManyModelsDeleted
{
    /** @var string */
    public $model;

    /**
     * Repository Many Models Deleted Event
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }
}