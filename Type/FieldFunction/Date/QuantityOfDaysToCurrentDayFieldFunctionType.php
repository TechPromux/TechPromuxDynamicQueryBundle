<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class QuantityOfDaysToCurrentDayFieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.QUANTITY_OF_DAYS_TO_CURRENT_DAY';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'QUANTITY_OF_DAYS_TO_CURRENT_DAY';
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
        return sprintf('TO_DAYS(CURRENT_DATE())- TO_DAYS(%s', $field_sql_name).')';
    }

}