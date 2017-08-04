<?php

namespace TechPromux\DynamicQueryBundle\Type\ValueFormat;

/**
 * Interface BaseValueFormatType
 *
 * @package  TechPromux\DynamicQueryBundle\Type\ValueFormat
 */
interface BaseValueFormatType
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getGroupName();

    /**
     * @return array
     */
    public function getAllowedValuesTypes();

    /**
     * @return boolean
     */
    public function getIsAllowedForValueType($type);

    /**
     * @return string
     */
    public function getReturnedValueType();

    /**
     * @param any $value
     * @return mixed
     */
    public function getFormattedValue($value);

}
