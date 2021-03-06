<?php

namespace TechPromux\DynamicQueryBundle\Type\DynamicValue;

/**
 * Interface BaseDynamicValueType
 *
 * @package  TechPromux\DynamicQueryBundle\Type\DynamicValue
 */
interface BaseDynamicValueType
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
     * @return string
     */
    public function getReturnedValueType();

    /**
     * @return mixed
     */
    public function getDynamicValue();

}
