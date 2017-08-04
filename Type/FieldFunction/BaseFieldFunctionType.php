<?php

namespace TechPromux\DynamicQueryBundle\Type\FieldFunction;

/**
 * Interface BaseFieldFunctionType
 *
 * @package  TechPromux\DynamicQueryBundle\Type\FieldFunction
 */
interface BaseFieldFunctionType
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
     * @return boolean
     */
    public function getHasAggregation();

    /**
     * @return string
     */
    public function getReturnedValueType();

    /**
     * @param string $field_sql_name
     * @return string
     */
    public function getAppliedFunctionToField($field_sql_name);


}