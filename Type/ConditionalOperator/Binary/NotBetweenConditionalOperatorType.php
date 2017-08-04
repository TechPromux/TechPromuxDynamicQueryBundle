<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/06/2017
 * Time: 00:52
 */

namespace TechPromux\DynamicQueryBundle\Type\ConditionalOperator\Binary;


use TechPromux\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;

class NotBetweenConditionalOperatorType implements BaseConditionalOperatorType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'BINARY.NOT_BETWEEN';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'NOT_BETWEEN';
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
        return array('number', 'integer', 'smallint', 'bigint', 'decimal', 'float', 'double', 'date', 'time', 'datetime');
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
        $str_condition = $left_operand . ' NOT BETWEEN ' . array_values($right_operand)[0] . ' AND ' . array_values($right_operand)[1];
        return $str_condition;
    }
}