<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use MongoDB\Laravel\Eloquent\Builder as MongoBuilder;
use App\Database\Query\MongoQueryBuilder;

trait HybridCompatibility
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder|\MongoDB\Laravel\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\MongoDB\Laravel\Eloquent\Builder
     */
    public function newEloquentBuilder($query)
    {
        if ($this->getConnection()->getDriverName() === 'mongodb') {
            return new MongoBuilder($query);
        }

        return parent::newEloquentBuilder($query);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        if ($connection->getDriverName() === 'mongodb') {
            return new MongoQueryBuilder($connection, $connection->getQueryGrammar(), $connection->getPostProcessor());
        }

        return parent::newBaseQueryBuilder();
    }

    /**
     * Get the parent relation of the model for MongoDB compatibility.
     *
     * @return null
     */
    public function getParentRelation()
    {
        return null;
    }

    /**
     * Get the projections of the model for MongoDB compatibility.
     *
     * @return array
     */
    public function getProjections()
    {
        return [];
    }
}
