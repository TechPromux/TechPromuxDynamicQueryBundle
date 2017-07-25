<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace  TechPromux\DynamicQueryBundle\Type\FieldFunction\Date;

use  TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class QuantityOfWeeksToCurrentWeekFieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.QUANTITY_OF_WEEKS_TO_CURRENT_WEEK';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'QUANTITY_OF_WEEKS_TO_CURRENT_WEEK';
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
        return sprintf('FLOOR((TO_DAYS(CURRENT_DATE())-2)/7)-FLOOR((TO_DAYS(%s', $field_sql_name).')-2)/7)';
    }

}