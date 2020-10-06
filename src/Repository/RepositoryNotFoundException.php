<?php

namespace Lerouse\LaravelRepository\Repository;

use Exception;

class RepositoryNotFoundException extends Exception
{

    /**
     * Create a new exception instance.
     *
     * @param string $namespace
     * @return void
     */
    public function __construct(string $namespace)
    {
        $message = sprintf('Model Repository \'%s\' not found.', $namespace);

        parent::__construct($message);
    }

}