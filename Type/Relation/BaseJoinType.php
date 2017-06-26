<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Type\Relation;

use Doctrine\ORM\QueryBuilder;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation;

/**
 * Interface BaseJoinType
 *
 * @package TechPromux\Bundle\DynamicQueryBundle\Type\Relation
 */
interface BaseJoinType
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