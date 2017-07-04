<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class DateOfFirstDayOfWeekFieldFunctionType implements BaseFieldFunctionType
{
    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.DATE_OF_FIRST_DAY_OF_WEEK';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'DATE_OF_FIRST_DAY_OF_WEEK';
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
        return 'date';
    }

    /**
     * @param string $field_sql_name
     * @return string
     */
    public function getAppliedFunctionToField($field_sql_name)
    {
        return sprintf('DATE_FORMAT(DATE_SUB(%s,INTERVAL (DAYOFWEEK(%s)-2) DAY),', $field_sql_name, $field_sql_name).'\'%Y-%m-%d 00:00:00\')';
    }

}