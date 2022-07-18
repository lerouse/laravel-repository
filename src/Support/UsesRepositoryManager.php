<?php

namespace Lerouse\LaravelRepository\Support;


use Lerouse\LaravelRepository\RepositoryInterface;
use Lerouse\LaravelRepository\Services\RepositoryManagerService;

trait UsesRepositoryManager
{
    private ?RepositoryManagerService $rm = null;

    public function getRepositoryManager(): RepositoryManagerService
    {
        if ($this->rm === null) {
            $this->rm = app()->make(RepositoryManagerService::class);
        }

        return $this->rm;
    }

    public function getRepository(string $repository): RepositoryInterface
    {
        return $this->getRepositoryManager()->getRepository($repository);
    }

}
