<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/06/2017
 * Time: 00:52
 */

namespace  TechPromux\DynamicQueryBundle\Type\ConditionalOperator\Unary;


use  TechPromux\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;

class IsNullConditionalOperatorType implements BaseConditionalOperatorType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'UNARY.IS_NULL';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'IS_NULL';
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
        return array('*', 'all', 'number', 'datetime', 'guid', 'integer', 'smallint', 'bigint', 'decimal', 'float', 'double', 'boolean', 'bool', 'date', 'time', 'datetime', 'string', 'text', 'char');
    }

    /**
     * @return boolean
     */
    public function getIsAllowedForValueType($type)
    {
        return true;
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
        $str_condition = $left_operand . ' IS NULL';
        return $str_condition;
    }
}