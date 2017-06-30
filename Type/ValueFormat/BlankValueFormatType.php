<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 26/06/2017
 * Time: 22:56
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\ValueFormat;


class BlankValueFormatType implements BaseValueFormatType
{
    /**
     * @return string
     */
    public function getId()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getAllowedValuesTypes()
    {
        return array('*', 'all');
    }

    /**
     * @return boolean
     */
    public function getIsAllowedForValueType($type)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getReturnedValueType()
    {
        return null;
    }

    /**
     * @param any $value
     * @return mixed
     */
    public function getFormattedValue($value)
    {
        return  $value;
    }
}