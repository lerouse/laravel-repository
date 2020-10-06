<?php

namespace Lerouse\LaravelRepository\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Lerouse\LaravelRepository\HasRepository;

class TestModel extends Model
{
    use HasRepository;

    /** {@inheritdoc} */
    protected $fillable = [
        'value'
    ];
}