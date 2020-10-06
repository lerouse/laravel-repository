<?php

/* @var $factory Factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Lerouse\LaravelRepository\Tests\Fixtures\Models\TestModel;

$factory->define(TestModel::class, static function (Faker $faker) {
    return [
        'value' => $faker->text(32)
    ];
});
