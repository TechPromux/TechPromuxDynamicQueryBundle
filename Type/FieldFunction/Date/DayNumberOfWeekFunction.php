<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunction;

class DayNumberOfWeekFunction implements BaseFieldFunction
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.DAY_NUMBER_OF_WEEK';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'DAY_NUMBER_OF_WEEK';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return 'DATE';
    }

    /**
     * @return boolean
     */
    public function getHasAggregation()
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getReturnedValueType()
    {
        return 'integer';
    }

    /**
     * @param string $field_sql_name
     * @return string
     */
    public function getAppliedFunctionToField($field_sql_name)
    {
        return sprintf('DAYOFWEEK(%s)', $field_sql_name);
    }

}