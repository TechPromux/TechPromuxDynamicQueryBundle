<?php

/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 26/06/2017
 * Time: 23:37
 */

namespace  TechPromux\DynamicQueryBundle\Type\ValueFormat\Date;


use  TechPromux\DynamicQueryBundle\Type\ValueFormat\BaseValueFormatType;

class ToDateYmdValueFormatType implements BaseValueFormatType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'DATE.TO_DATE_Y-m-d';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'DATE.TO_DATE_Y-m-d';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return 'DATE';
    }

    /**
     * @return array
     */
    public function getAllowedValuesTypes()
    {
        return array('date', 'datetime');
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
        return 'date';
    }

    /**
     * @param any $value
     * @return mixed
     */
    public function getFormattedValue($value)
    {
        $formatted_value = (\DateTime::createFromFormat('Y-m-d', $value) ?
            \DateTime::createFromFormat('Y-m-d', $value)
            : \DateTime::createFromFormat('Y-m-d H:i:s', $value))
            ->format('Y-m-d');
        return $formatted_value;
    }
}