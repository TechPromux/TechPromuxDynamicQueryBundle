<?php

namespace  TechPromux\DynamicQueryBundle\Type\ConditionalOperator;

/**
 * Interface BaseConditionalOperatorType
 *
 * @package  TechPromux\DynamicQueryBundle\Type\ConditionalOperator
 */
interface BaseConditionalOperatorType
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
    public function getIsUnary();

    /**
     * @return array
     */
    public function getAllowedValuesTypes();

    /**
     * @return boolean
     */
    public function getIsAllowedForValueType($type);

    /**
     * @return boolean
     */
    public function getIsForRightOperandAsArray();

    /**
     * @param mixed $left_operand
     * @param mixed $right_operand
     * @return mixed
     */
    public function getConditionalStatement($left_operand, $right_operand = null);

}
