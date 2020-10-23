<?php

namespace Lerouse\LaravelRepository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RepositoryInterface
{

    /**
     * Get all Models
     *
     * @return mixed
     */
    public function all();

    /**
     * Get all models as Paginated Results
     *
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function paginate(int $limit = null): LengthAwarePaginator;

    /**
     * Find a Model by its Primary Key
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create a new Model
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Create a collection of new Models
     *
     * @param array $data
     * @return mixed
     */
    public function createMany(array $data);

    /**
     * Update a Model by its Primary Key
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * Update a collection of Models
     *
     * @param array $data
     * @param array $ids
     * @param string|null $column
     * @return mixed
     */
    public function updateMany(array $data, array $ids, string $column = null);

    /**
     * Delete a Model by its Primary Key
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);


    /**
     * Delete a collection of Models by their Primary Keys
     *
     * @param array $ids
     * @param string|null $column
     * @return mixed
     */
    public function deleteMany(array $ids, string $column = null);

}