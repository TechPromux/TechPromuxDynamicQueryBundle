<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace  TechPromux\DynamicQueryBundle\Type\FieldFunction\Date;

use  TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class DayNameOfWeekFieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.DAY_NAME_OF_WEEK';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'DAY_NAME_OF_WEEK';
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
        return 'string';
    }

    /**
     * @param string $field_sql_name
     * @return string
     */
    public function getAppliedFunctionToField($field_sql_name)
    {
        return sprintf('DAYNAME(%s)', $field_sql_name);
    }

}