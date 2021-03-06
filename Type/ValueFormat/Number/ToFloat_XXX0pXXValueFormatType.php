<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 26/06/2017
 * Time: 22:56
 */

namespace TechPromux\DynamicQueryBundle\Type\ValueFormat\Number;


use TechPromux\DynamicQueryBundle\Type\ValueFormat\BaseValueFormatType;

class ToFloat_XXX0pXXValueFormatType implements BaseValueFormatType
{
    /**
     * @return string
     */
    public function getId()
    {
        return 'NUMBER.TO_FLOAT_###0.##';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'TO_FLOAT_###0.##';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return 'NUMBER';
    }

    /**
     * @return array
     */
    public function getAllowedValuesTypes()
    {
        return array('number', 'number', 'int', 'integer', 'float', 'double');
    }

    /**
     * @return boolean
     */
    public function getIsAllowedForValueType($type)
    {
        return in_array($type, $this->getAllowedValuesTypes());
    }

    /**
     * @return string
     */
    public function getReturnedValueType()
    {
        return 'float';
    }

    /**
     * @param any $value
     * @return mixed
     */
    public function getFormattedValue($value)
    {
        $thousand_separator = '';
        $decimal_pointer = '.';
        $decimal_digits = 2;
        $formatted_value = number_format($value, $decimal_digits, $decimal_pointer, $thousand_separator);
        return $formatted_value;
    }
}