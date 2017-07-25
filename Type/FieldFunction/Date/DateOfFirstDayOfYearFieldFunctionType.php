<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace  TechPromux\DynamicQueryBundle\Type\FieldFunction\Date;

use  TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class DateOfFirstDayOfYearFieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.DATE_OF_FIRST_DAY_OF_YEAR';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'DATE_OF_FIRST_DAY_OF_YEAR';
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
        return sprintf('DATE_FORMAT(%s,', $field_sql_name).'\'%Y-01-01 00:00:00\')';
    }

}