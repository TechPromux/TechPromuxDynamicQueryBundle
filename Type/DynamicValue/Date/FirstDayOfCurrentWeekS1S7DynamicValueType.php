<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 02/07/2017
 * Time: 18:53
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\DynamicValue\Date;


use TechPromux\Bundle\DynamicQueryBundle\Type\DynamicValue\BaseDynamicValueType;

class FirstDayOfCurrentWeekS1S7DynamicValueType implements BaseDynamicValueType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.FIRST_DATE_OF_CURRENT_WEEK_S1S7';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'FIRST_DATE_OF_CURRENT_WEEK_S1S7';
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
        $value = date("Y-m-d 00:00:00", strtotime(date("Y-m-d")) - (3600 * 24) * (date("w")));
        return $value;
    }
}