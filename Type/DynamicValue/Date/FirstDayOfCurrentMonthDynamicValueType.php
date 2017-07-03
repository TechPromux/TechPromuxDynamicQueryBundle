<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 02/07/2017
 * Time: 18:53
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\DynamicValue\Date;


use TechPromux\Bundle\DynamicQueryBundle\Type\DynamicValue\BaseDynamicValueType;

class FirstDayOfCurrentMonthDynamicValueType implements BaseDynamicValueType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.FIRST_DATE_OF_CURRENT_MONTH';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'FIRST_DATE_OF_CURRENT_MONTH';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return 'DATE';
    }

    /**
     * @return string
     */
    public function getReturnedValueType()
    {
        return 'datetime';
    }

    /**
     * @return mixed
     */
    public function getDynamicValue()
    {
        $value = date("Y-m-01 00:00:00");
        return $value;
    }
}