<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction;

use Doctrine\ORM\QueryBuilder;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField;

/**
 * Interface BaseFieldFunction
 *
 * @package TechPromux\Bundle\DynamicQueryBundle\Type\FieldFunction
 */
interface BaseFieldFunction
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