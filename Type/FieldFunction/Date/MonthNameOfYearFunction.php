<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 15/06/2017
 * Time: 11:30
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\Date;

use TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction\BaseFieldFunction;

class MonthNameOfYearFunction implements BaseFieldFunction
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.MONTH_NAME_OF_YEAR';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'MONTH_NAME_OF_YEAR';
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
        return sprintf('MONTHNAME(%s)', $field_sql_name);
    }

}