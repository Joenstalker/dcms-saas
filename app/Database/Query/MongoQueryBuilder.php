<?php

namespace App\Database\Query;

use MongoDB\Laravel\Query\Builder as BaseMongoQueryBuilder;

class MongoQueryBuilder extends BaseMongoQueryBuilder
{
    /**
     * @inheritdoc
     */
    public function whereIntegerInRaw($column, $values, $boolean = 'and', $not = false)
    {
        return $this->whereIn($column, $values, $boolean, $not);
    }

    /**
     * @inheritdoc
     */
    public function orWhereIntegerInRaw($column, $values)
    {
        return $this->whereIn($column, $values, 'or');
    }

    /**
     * @inheritdoc
     */
    public function whereIntegerNotInRaw($column, $values, $boolean = 'and')
    {
        return $this->whereNotIn($column, $values, $boolean);
    }

    /**
     * @inheritdoc
     */
    public function orWhereIntegerNotInRaw($column, $values, $boolean = 'and')
    {
        return $this->whereNotIn($column, $values, 'or');
    }
}
