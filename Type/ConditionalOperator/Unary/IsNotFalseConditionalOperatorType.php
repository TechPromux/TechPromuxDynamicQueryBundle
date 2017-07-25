<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/06/2017
 * Time: 00:52
 */

namespace  TechPromux\DynamicQueryBundle\Type\ConditionalOperator\Unary;


use  TechPromux\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;

class IsNotFalseConditionalOperatorType implements BaseConditionalOperatorType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'UNARY.IS_NOT_FALSE';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'IS_NOT_FALSE';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return 'UNARY';
    }

    /**
     * @return boolean
     */
    public function getIsUnary()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getAllowedValuesTypes()
    {
        return array('bool', 'boolean');
    }

    /**
     * @return boolean
     */
    public function getIsAllowedForValueType($type)
    {
        return in_array($type, $this->getAllowedValuesTypes());
    }

    /**
     * @return boolean
     */
    public function getIsForRightOperandAsArray()
    {
        return false;
    }

    /**
     * @param mixed $left_operand
     * @param mixed $right_operand
     * @return string
     */
    public function getConditionalStatement($left_operand, $right_operand = null)
    {
        $str_condition = $left_operand . ' IS NOT FALSE';
        return $str_condition;
    }
}