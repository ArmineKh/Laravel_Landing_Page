<?php
/**
 * Created by PhpStorm.
 * User: ateeq-ahmed
 * Date: 14/4/17
 * Time: 11:25 AM
 */

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\ServiceProvider;

class ListsWorkAround extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        Builder::macro("lists", function ($column, $key = null) {
            return $this->pluck($column, $key)->all();
        });

        QueryBuilder::macro("lists", function ($column, $key = null) {
            return $this->pluck($column, $key)->all();
        });
    }
}