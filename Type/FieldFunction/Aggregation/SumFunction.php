<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Aggregation;

use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunction;

class SumFunction implements BaseFieldFunction
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'AGGREGATION.SUM';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'SUM';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return 'AGGREGATION';
    }

    /**
     * @return boolean
     */
    public function getHasAggregation()
    {
        return true;
    }

    /**
     * @return string|null
     */
    public function getReturnedValueType()
    {
        return null;
    }

    /**
     * @param string $field_sql_name
     * @return string
     */
    public function getAppliedFunctionToField($field_sql_name)
    {
        return sprintf('SUM(%s)', $field_sql_name);
    }

}