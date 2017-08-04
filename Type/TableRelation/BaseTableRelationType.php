<?php

namespace TechPromux\DynamicQueryBundle\Type\TableRelation;

use Doctrine\ORM\QueryBuilder;
use TechPromux\DynamicQueryBundle\Entity\MetadataRelation;

/**
 * Interface BaseTableRelationType
 *
 * @package  TechPromux\DynamicQueryBundle\Type\TableRelation
 */
interface BaseTableRelationType
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
     * @param QueryBuilder $queryBuilder
     * @param MetadataRelation $metadata_relation
     * @return QueryBuilder
     */
    public function appendToQuery($queryBuilder, $metadata_relation);


}