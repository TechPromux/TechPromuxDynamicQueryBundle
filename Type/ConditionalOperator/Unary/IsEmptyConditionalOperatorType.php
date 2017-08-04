<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/06/2017
 * Time: 00:52
 */

namespace TechPromux\DynamicQueryBundle\Type\ConditionalOperator\Unary;


use TechPromux\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;

class IsEmptyConditionalOperatorType implements BaseConditionalOperatorType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'UNARY.IS_EMPTY';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'IS_EMPTY';
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
        return array('string', 'guid', 'string', 'text', 'char');
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
        $str_condition = $left_operand . ' = ' . '\'\'';
        return $str_condition;
    }
}