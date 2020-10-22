<?php

namespace Lerouse\LaravelRepository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lerouse\LaravelRepository\Repository\RepositoryNotFoundException;

trait HasRepository
{

    /**
     * Get the Repository
     *
     * @return RepositoryInterface
     * @throws RepositoryNotFoundException
     */
    public function getRepository(): RepositoryInterface
    {
        $namespace = $this->getRepositoryNamespace() . strrchr(__CLASS__, '\\') . 'Repository';

        if (!class_exists($namespace)) {
            throw new RepositoryNotFoundException($namespace);
        }

        return new $namespace;
    }

    /**
     * Get Repository Namespace
     *
     * @return string
     */
    public function getRepositoryNamespace(): string
    {
        return config('repository.namespace', '\App\Repositories\Model');
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        try {
            return $this->getRepository()->find($value);
        } catch (RepositoryNotFoundException $e) {
            return parent::resolveRouteBinding($value, $field);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

}