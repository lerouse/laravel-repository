<?php

namespace Lerouse\LaravelRepository\Services;

use Lerouse\LaravelRepository\Repository\RepositoryNotFoundException;
use Lerouse\LaravelRepository\RepositoryInterface;

class RepositoryManagerService
{

    public function __construct(protected string $repositoryNamespace) {}

    public function getRepository(string $repository): RepositoryInterface
    {
        $namespace = sprintf(
            '%s\\%s%s',
            $this->getRepositoryNamespace(),
            $this->sanitiseRepositoryName($repository),
            $this->getNamespaceAppend($repository)
        );

        if (!class_exists($namespace)) {
            throw new RepositoryNotFoundException($namespace);
        }

        return new $namespace;
    }

    protected function sanitiseRepositoryName(string $repository): string
    {
        preg_match('/(\\\+)/i', $repository, $matches);

        if (count($matches) > 0) {
            return substr(strrchr($repository, '\\'), 1);
        }

        return $repository;
    }

    private function getRepositoryNamespace(): string
    {
        return $this->repositoryNamespace;
    }

    private function getNamespaceAppend(string $repository): string|null
    {
        preg_match('/([a-z]+)Repository$/i', $repository, $matches);

        if (count($matches) === 0) {
            return 'Repository';
        }

        return null;
    }

}