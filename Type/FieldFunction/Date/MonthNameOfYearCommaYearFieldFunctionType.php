<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunctionType;

class MonthNameOfYearCommaYearFieldFunctionType implements BaseFieldFunctionType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.MONTH_NAME_OF_YEAR_COMMA_YEAR';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'MONTH_NAME_OF_YEAR_COMMA_YEAR';
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
        return sprintf('CONCAT(MONTHNAME(%s),\', \', YEAR(%s))', $field_sql_name, $field_sql_name);
    }

}