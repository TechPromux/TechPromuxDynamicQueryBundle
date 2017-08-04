<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class WeekNumberOfMonthFieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.WEEK_NUMBER_OF_MONTH';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'WEEK_NUMBER_OF_MONTH';
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
        return sprintf('FLOOR((DAYOFMONTH(%s)+DAYOFWEEK(DATE_FORMAT(%s', $field_sql_name, $field_sql_name) . ',\'%Y-%m-01\'))-2)/7)+1';
    }

}