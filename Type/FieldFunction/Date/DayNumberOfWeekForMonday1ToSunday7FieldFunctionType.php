<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class DayNumberOfWeekForMonday1ToSunday7FieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.DAY_NUMBER_OF_WEEK_FOR_MONDAY_1_TO_SUNDAY_7';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'DAY_NUMBER_OF_WEEK_FOR_MONDAY_1_TO_SUNDAY_7';
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
        return sprintf('WEEKDAY(%s)+1', $field_sql_name);
    }

}