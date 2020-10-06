<?php

namespace Lerouse\LaravelRepository\Events;

class ManyModelsCreated
{
    /** @var string */
    public $model;

    /**
     * Repository Many Models Created Event
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }
}