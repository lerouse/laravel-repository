<?php

/* @var $factory Factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\FailedModel;

$factory->define(FailedModel::class, static function (Faker $faker) {
    return [];
});
