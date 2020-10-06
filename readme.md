# Laravel Model Repository

<a href="https://travis-ci.org/lerouse/laravel-repository"><img src="https://travis-ci.org/lerouse/laravel-repository.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/lerouse/laravel-repository"><img src="https://img.shields.io/packagist/v/lerouse/laravel-repository" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/lerouse/laravel-repository"><img src="https://img.shields.io/packagist/l/lerouse/laravel-repository" alt="License"></a>

Model Repository implementation for the Laravel framework v5.6 and above.

This package provides a structured framework to begin implementing a simple Model Repository structure in an existing 
Laravel application. 

## Installation

The recommended method to install LaravelRepository is with [composer](https://getcomposer.org)

```bash
php composer require lerouse/laravel-repository
```

### Laravel without auto-discovery

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
\Lerouse\LaravelRepository\LaravelRepositoryServiceProvider::class,
```

### Package configuration

Copy the package configuration to your local config directory.

```bash
php artisan vendor:publish --tag=repository-config
```

## Usage

### Model Repository Location

By default Laravel Model Repository presumes that model repositories are stored in the directory 
`app/repositories/model`. If you would like to change the directory your model repositories are stored change the namespace
variable in the `config/repostiory.php` config file.

```php
'namespace' => '\App\Repositories\Model',
```

For example if your model repositories are located in `app/model/repositories` update the namespace to

 ```php
'namespace' => '\App\Model\Repositories',
 ```

### Creating a Repository

Repositories should extend the `Lerouse\LaravelRepository\EloquentRepository` method and implement the builder method 
which should return an instance of a `Illuminate\Database\Eloquent\Builder`.


 ```php
<?php

namespace App\Repositories\Models;

use Illuminate\Database\Eloquent\Builder;
use Lerouse\LaravelRepository\EloquentRepository;
use App\Models\User;

class UserRepository extends EloquentRepository
{

    /**
     * Get the Repository Query Builder
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return User::query();
    }

}
 ```

The following methods are available out of the box:-

| Method                | Return Type           | Description                                         |
| --------------------- | --------------------- | --------------------------------------------------- |
| ```all```             | Collection            | Get all Models                                      |
| ```paginate```        | LengthAwarePaginator  | Get all models as Paginated Results                 |
| ```find```            | Model                 | Find a Model by its Primary Key                     |
| ```create```          | Model                 | Create a new Model                                  |
| ```createMany```      | Boolean               | Create a collection of new Models                   |
| ```update```          | Model                 | Update a Model by its Primary Key                   |
| ```updateMany```      | Boolean               | Update a collection of Models                       |
| ```delete```          | Model|null            | Delete a Model by its Primary Key                   |
| ```deleteMany```      | void                  | Delete a collection of Models by their Primary Keys |

### Using a Repository

Repositories can be used directly (see above for available built in methods).

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;

class UserController
{

    /**
     * Get the Repository Query Builder
     *
     * @return Builder
     */
    public function index(): Builder
    {
        $repository = new UserRepository;

        return $repository->all(); 
    }

}
```

### Extending a Repository

Repositories should be the single point for extending/amending queries to your models. The advantage of this is all of 
your model business logic is in a single location. See below a repository that contains commonly used extended 
functionality as well as some custom methods that are also used. 

 ```php
<?php

namespace App\Repositories\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Lerouse\LaravelRepository\EloquentRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Team;
use App\Models\User;

class UserRepository extends EloquentRepository
{
    
    /**
     * Get all models as Paginated Results
     *
     * @param int|null $limit
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginate(int $limit = null, int $page = 1): LengthAwarePaginator
    {
        return $this->builder()
                    ->orderBy($this->getModelTable() . '.created_at', 'desc')
                    ->paginate($limit ?? 25, $page);
    }

    /**
     * Get active Users
     *
     * @return Collection
     */
    public function active(): Collection
    {
        return $this->builder()
                    ->where($this->getModelTable() . 'active', '=', true)
                    ->orderBy($this->getModelTable() . '.created_at', 'desc')
                    ->get();
    }

    /**
     * Get the Repository Query Builder
     *
     * @return Builder
     */
    public function builder(): Builder
    {
        return User::query()
                    ->select($this->getModelTable() . '.*')
                    ->selectRaw('teams.name as user_team_name')
                    ->leftJoin('teams', 'teams.id', '=', $this->getModelTable() . '.team_id');
    }

}
 ```

### Laravel Route Model Binding

Laravel route model binding provides a convenient way to automatically inject the model instances directly into your 
routes. To force the route model binding to use your repository rather than the model itself you can add the 
```Lerouse\LaravelRepository\HasRepository``` trait to your model.

 ```php
<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Lerouse\LaravelRepository\HasRepository;

class User extends Model
{
    use HasRepository;
}
```

Now when you utilise Laravel's Route Model Binding, the find method on the repository will be used.

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{

    /**
     * Get the Repository Query Builder
     *
     * @return Builder
     */
    public function show(User $user): Builder // binding resolved through the UserRepository's find method 
    {
        return $user; 
    }

}
```

Please Note: The repositories location is determined via the namespace variable in the `config/repostiory.php` 
config file. 

```php
'namespace' => '\App\Repositories\Model',
```

## License

Laravel Model Repository is free software distributed under the terms of the MIT license.