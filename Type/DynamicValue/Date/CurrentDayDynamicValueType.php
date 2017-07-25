<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 02/07/2017
 * Time: 18:53
 */

namespace  TechPromux\DynamicQueryBundle\Type\DynamicValue\Date;


use  TechPromux\DynamicQueryBundle\Type\DynamicValue\BaseDynamicValueType;

class CurrentDayDynamicValueType implements BaseDynamicValueType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.CURRENT_DAY';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'CURRENT_DAY';
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
        $value = date("Y-m-d 00:00:00");
        return $value;
    }
}