<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/06/2017
 * Time: 00:52
 */

namespace  TechPromux\DynamicQueryBundle\Type\ConditionalOperator\Binary;


use  TechPromux\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;

class NotInConditionalOperatorType implements BaseConditionalOperatorType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'BINARY.NOT_IN';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'NOT_IN';
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return 'BINARY';
    }

    /**
     * @return boolean
     */
    public function getIsUnary()
    {
        return false;
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
        return true;
    }

    /**
     * @param mixed $left_operand
     * @param mixed $right_operand
     * @return string
     */
    public function getConditionalStatement($left_operand, $right_operand = null)
    {
        $str_condition = $left_operand . ' NOT IN (' . implode(',', $right_operand) . ')';
        return $str_condition;
    }
}