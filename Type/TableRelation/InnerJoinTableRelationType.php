<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 31/05/2017
 * Time: 12:31
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Type\TableRelation;


use Doctrine\ORM\QueryBuilder;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation;

class InnerJoinTableRelationType implements BaseTableRelationType
{

    /**
     * @return string
     */
    public function getId()
    {
        return 'JOIN';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'JOIN';
    }

    /**
     * @param QueryBuilder $query
     * @param MetadataRelation $metadata_relation
     * @return QueryBuilder
     */
    public function appendToQuery($query, $metadata_relation)
    {
        $left_table = $metadata_relation->getLeftTable();
        $left_column = $metadata_relation->getLeftColumn();
        $left_table_sql_alias = $left_table->getSQLAlias();

        $right_table = $metadata_relation->getRightTable();
        $right_column = $metadata_relation->getRightColumn();
        $right_table_sql_name = $right_table->getSQLName();
        $right_table_sql_alias = $right_table->getSQLAlias();

        $query
            ->innerJoin($left_table_sql_alias,
                $right_table_sql_name,
                $right_table_sql_alias,
                $left_table_sql_alias . '.' . $left_column . '=' . $right_table_sql_alias . '.' . $right_column
            );

        return $query;
    }
}