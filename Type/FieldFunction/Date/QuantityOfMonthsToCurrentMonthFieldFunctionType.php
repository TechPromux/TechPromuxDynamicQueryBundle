<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class QuantityOfMonthsToCurrentMonthFieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.QUANTITY_OF_MONTHS_TO_CURRENT_MONTH';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'QUANTITY_OF_MONTHS_TO_CURRENT_MONTH';
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
        return 'PERIOD_DIFF(DATE_FORMAT(CURRENT_DATE(),\'%Y%m\')'.sprintf(', DATE_FORMAT(%s', $field_sql_name).',\'%Y%m\'))';
    }

}