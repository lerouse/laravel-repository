<?php

namespace Lerouse\LaravelRepository\Events;

class ManyModelsUpdated
{
    /** @var string */
    public $model;

    /**
     * Repository Many Models Updated Event
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }
}