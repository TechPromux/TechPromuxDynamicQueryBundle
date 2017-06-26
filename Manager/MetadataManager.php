<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/05/2017
 * Time: 01:01
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable;
use TechPromux\Bundle\DynamicQueryBundle\Type\Relation\BaseJoinType;
use TechPromux\Bundle\DynamicQueryBundle\Type\Relation\InnerJoinType;
use TechPromux\Bundle\DynamicQueryBundle\Type\Relation\LeftJoinType;

class MetadataManager extends BaseResourceManager
{

    /**
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'TechPromuxDynamicQueryBundle';
    }

    /**
     * Obtiene la clase de la entidad
     *
     * @return class
     */
    public function getResourceClass()
    {
        return Metadata::class;
    }

    /**
     * Obtiene el nombre corto de la entidad
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'Metadata';
    }

    //-------------------------------------------------------------------------------------

    /**
     * @var DynamicQueryUtilManager
     */
    protected $util_manager;

    /**
     * @return DynamicQueryUtilManager
     */
    public function getDynamicQueryUtilManager()
    {
        return $this->util_manager;
    }

    /**
     * @param DynamicQueryUtilManager $util_manager
     * @return DataModelManager
     */
    public function setDynamicQueryUtilManager($util_manager)
    {
        $this->util_manager = $util_manager;
        return $this;
    }

    //-------------------------------------------------------------------------------------

    /**
     * @return object|DataSourceManager
     */
    public function getDataSourceManager()
    {
        // TODO: replace with services injection

        return $this->getServiceContainer()->get('techpromux_dynamic_query.manager.datasource');
    }

    /**
     * @return object|MetadataTableManager
     */
    public function getMetadataTableManager()
    {
        // TODO: replace with services injection
        // TODO create property and set it by container configuration
        return $this->getServiceContainer()->get('techpromux_dynamic_query.manager.metadata_table');
    }

    /**
     * @return object|MetadataFieldManager
     */
    public function getMetadataFieldManager()
    {
        // TODO create property and set it by container configuration
        return $this->getServiceContainer()->get('techpromux_dynamic_query.manager.metadata_field');
    }

    /**
     * @return object|MetadataRelationManager
     */
    public function getMetadataRelationManager()
    {
        // TODO create property and set it by container configuration
        return $this->getServiceContainer()->get('techpromux_dynamic_query.manager.metadata_relation');
    }

    //--------------------------------------------------------------------------------

    /**
     *
     * @param Metadata $object
     * @return Metadata
     */
    public function createCopyForMetadata(Metadata $object)
    {
        $manager = $this;
        /* @var $manager MetadataManager */
        $new_metadata = null;

        $this->executeTransaction(function () use ($manager, $object, &$new_metadata) {
            $metadata = $manager->find($object->getId());
            /* @var $metadata Metadata */

            $new_metadata = $manager->createNewInstance();
            /* @var $new_metadata Metadata */

            $new_metadata->setDataSource($metadata->getDataSource());
            $new_metadata->setName($metadata->getName() . '__COPY__' . (new \DateTime())->format('YmdHis'));
            $new_metadata->setTitle($metadata->getTitle() . ' (' . $manager->trans('COPY') . ')');
            $new_metadata->setDescription($metadata->getDescription());

            $manager->persist($new_metadata);

            $tables = $manager->getMetadataTableManager()->findBy(array('metadata' => $metadata->getId()));

            $tables_to_copy = array();

            foreach ($tables as $tbl) {
                /* @var $tbl MetadataTable */

                $new_table = $manager->getMetadataTableManager()->createNewInstance();
                /* @var $new_table MetadataTable */

                $new_table->setName($tbl->getName());
                $new_table->setTitle($tbl->getTitle());
                $new_table->setType($tbl->getType());
                $new_table->setTableName($tbl->getTableName());
                $new_table->setCustomQuery($tbl->getCustomQuery());
                $new_table->setCustomQueryFields($tbl->getCustomQueryFields());
                $new_table->setPosition($tbl->getPosition());

                $new_table->setMetadata($new_metadata);
                $new_metadata->addTable($new_table);

                $tables_to_copy[$tbl->getId()] = $new_table;

                $manager->getMetadataTableManager()->persist($new_table);
            }

            $fields = $this->getMetadataFieldManager()->findBy(array('metadata' => $metadata->getId()));

            foreach ($fields as $fld) {
                /* @var $fld MetadataField */

                $new_field = $this->getMetadataFieldManager()->createNewInstance();
                /* @var $new_field MetadataField */


                $new_field->setName($fld->getName());
                $new_field->setTitle($fld->getTitle());

                $new_field->setType($fld->getType());
                $new_field->setTable($tables_to_copy[$fld->getTable()->getId()]);
                $new_field->setEnabled($fld->getEnabled());
                $new_field->setPosition($fld->getPosition());

                $new_field->setMetadata($new_metadata);
                $new_metadata->addField($new_field);

                $manager->getMetadataFieldManager()->persist($new_field);
            }

            $relations = $this->getMetadataRelationManager()->findBy(array('metadata' => $metadata->getId()));

            foreach ($relations as $rls) {
                /* @var $rls MetadataRelation */

                $new_relation = $this->getMetadataRelationManager()->createNewInstance();
                /* @var $new_relation MetadataRelation */

                $new_relation->setName($rls->getName());
                $new_relation->setJoinType($rls->getJoinType());
                $new_relation->setLeftTable($tables_to_copy[$rls->getLeftTable()->getId()]);
                $new_relation->setLeftColumn($rls->getLeftColumn());
                $new_relation->setRightTable($tables_to_copy[$rls->getRightTable()->getId()]);
                $new_relation->setRightColumn($rls->getRightColumn());
                $new_relation->setPosition($rls->getPosition());

                $new_relation->setMetadata($new_metadata);
                $new_metadata->addRelation($new_relation);

                $manager->getMetadataRelationManager()->persist($new_relation);
            }
        });

        return $new_metadata;

    }

    /**
     *
     * @param MetadataTable $metadata_table
     * @return array
     */
    public function verifyCustomQueryFromMetadataTable(MetadataTable $metadata_table)
    {

        if ($metadata_table->getType() == 'query') {

            // ver qué hacer si la consulta está mal????? o si es de tipo insert o update (sql injection)
            // preguntar si es diferente al anterior valor.....para no tener que recalcular la consulta, ni tener que conectarse

            $datasource_connection = $metadata_table->getMetadata()->getConnection(); // ver si no se parte cuando es new el object, por si acaso obtener la conexion

            $sql = 'SELECT * FROM (SELECT 1 as ___F0) as ___T0 LEFT JOIN (' . $metadata_table->getCustomQuery() . ') AS ___T1 ON 1=1 LIMIT 0,1';

            try {
                $result = $this->getDataSourceConnectionManager()
                    ->getSQLResultFromDataSource($datasource_connection, $sql);

                $first_row = $result[0];

                $query_columns = array();

                $first = true;
                foreach (array_keys($first_row) as $fieldname) {
                    if (!$first) {
                        $query_columns[$fieldname] = array(
                            'name' => $fieldname,
                            'type' => $first_row[$fieldname] != null ? strtoupper(gettype($first_row[$fieldname])) : 'STRING'
                        );
                    }
                    $first = false;
                }
                return count($query_columns);
            } catch (\Doctrine\DBAL\Exception\TableNotFoundException $ex) {
                return null;
            } catch (\Exception $ex) {
                return null;
            }
        }
    }

    /**
     *
     * @param MetadataTable $metadata_table
     * @return MetadataTable
     */
    public function getFieldsDescriptionsFromCustomQuery(MetadataTable $metadata_table)
    {

        if ($metadata_table->getType() == 'query') {

            // ver qué hacer si la consulta está mal????? o si es de tipo insert o update (sql injection)
            // preguntar si es diferente al anterior valor.....para no tener que recalcular la consulta, ni tener que conectarse

            $sql = 'SELECT * FROM (SELECT 1 as ___F0) as ___T0 LEFT JOIN (' . $metadata_table->getCustomQuery() . ') AS ___T1 ON 1=1 LIMIT 0,1';

            $datasource = $metadata_table->getMetadata()->getDataSource(); // ver si no se parte cuando es new el object, por si acaso obtener la conexion

            $result = $this->getDataSourceManager()->getSQLResultFromDataSource($datasource, $sql);

            $first_row = $result[0];

            $query_fields = array();

            $first = true;
            foreach (array_keys($first_row) as $fieldname) {
                if (!$first) {
                    $query_fields[$fieldname] = array(
                        'name' => $fieldname,
                        'type' => $first_row[$fieldname] != null ? strtolower(gettype($first_row[$fieldname])) : 'string'
                    );
                }
                $first = false;
            }

            return $query_fields;
        }
        return null;

    }

    /**
     *
     * @param MetadataTable $metadataTable
     * @return array
     */
    public function getColumnsDescriptionsFromTable(MetadataTable $metadataTable)
    {

        // obtener la metadataTable de la base de datos

        $metadata = $metadataTable->getMetadata();

        if ($metadata == null) {
            $real_table = $this->getMetadataTableManager()->find($metadataTable->getId());
            $metadata = $real_table->getMetadata();
        }

        $datasource = $metadata->getDataSource();

        if ($datasource == null) {
            $real_metadata = $this->find($metadata->getId());
            $datasource = $real_metadata->getDataSource();
        }

        $metadatas_descriptions = $datasource->getMetadataInfo();

        $columns_descriptions = array();

        if ($metadataTable->getType() == 'table') {

            $table_name = $metadataTable->getTableName();

            $columns_descriptions = isset($metadatas_descriptions[$table_name]) ? $metadatas_descriptions[$table_name]['columns'] : array();

            foreach ($columns_descriptions as $column) {
                $columns_descriptions[$column['name']] = array('name' => $column['name'], 'type' => $column['type']);
            }
        } else {

            $columns_query_descriptions = $metadataTable->getCustomQueryFields();

            if ($columns_query_descriptions == null) {
                $columns_query_descriptions = array();
            }
            foreach ($columns_query_descriptions as $column) {
                $columns_descriptions[$column['name']] = array('name' => $column['name'], 'type' => $column['type']);
            }
        }

        return $columns_descriptions;
    }

    /**
     *
     * @param Metadata $metadata
     * @return array
     */
    public function getTablesAndColumnsDescriptionsFromMetadata(Metadata $metadata)
    {
        $tables = $this->getMetadataTableManager()->findBy(array('metadata' => $metadata->getId()));

        $descriptions = array();

        foreach ($tables as $table) {
            /* @var $table MetadataTable */

            $table->setMetadata($metadata);

            $table_descriptions = array();

            $table_descriptions['id'] = $table->getId();
            $table_descriptions['name'] = $table->getName();
            $table_descriptions['columns'] = array();

            $columns_descriptions = $this->getColumnsDescriptionsFromTable($table);

            foreach ($columns_descriptions as $column) {
                $table_descriptions['columns'][] = array('name' => $column['name'], 'type' => $column['type']);
            }

            $descriptions[] = $table_descriptions;
        }

        return $descriptions;
    }

    //--------------------------------------------------------------------------

    /**
     *
     * @param Metadata $metadata
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getQueryBuilderFromMetadata($metadata)
    {
        /* @var $queryBuilder \Doctrine\DBAL\Query\QueryBuilder */
        $queryBuilder = $this->getDataSourceManager()->createQueryBuilderFromDataSource($metadata->getDatasource());

        // ADD TABLES AND RELATIONSHIPS

        $added_tables_ids_aliasses = array();

        foreach ($metadata->getRelations() as $relation) {
            /* @var $relation MetadataRelation */

            $left_table = $relation->getLeftTable();
            $join_type = $relation->getJoinType();
            $right_table = $relation->getRightTable();

            if (!isset($added_tables_ids_aliasses[$left_table->getId()])) {
                $left_table_sql_name = $left_table->getSQLName();
                $left_table_sql_alias = $left_table->getSQLAlias();
                $queryBuilder->from($left_table_sql_name, $left_table_sql_alias);
                $added_tables_ids_aliasses[$left_table->getId()] = $left_table;
            }

            $join_type_options = $this->getDynamicQueryUtilManager()->getRegisteredRelationJoinTypes();
            $join_type_options_selected = $join_type_options[$join_type];
            $join_type_options_selected->appendToQuery($queryBuilder, $relation);

            $added_tables_ids_aliasses[$right_table->getId()] = $right_table->getSQLAlias();
        }

        // verificar todas las tablas del metadata, y las tablas asociadas a campos presentes en el select
        // si alguna de esas tablas no esta en el from agregarlas entonces???
        // si el from no tiene tabla, agregar alguna,

        foreach ($metadata->getTables() as $table) {
            /* @var $table MetadataTable */
            if (!isset($added_tables_ids_aliasses[$table->getId()])) {
                $queryBuilder->from($table->getSQLName(), $table->getSQLAlias());
                $added_tables_ids_aliasses[$table->getId()] = $table->getSQLAlias();
            }
        }

        return $queryBuilder;
    }

    //--------------------------------------------------------------------------

    /**
     * @param Metadata $object
     * @param bool $flushed
     */
    public function preUpdate($object, $flushed = true)
    {
        parent::preUpdate($object, $flushed);

        //----------------------------------
        $tables = array();
        foreach ($object->getTables() as $metadata_table) {
            /* @var $metadata_table MetadataTable */
            $tables[$metadata_table->getPosition()] = $metadata_table;
            if (is_null($metadata_table->getId())) {
                $this->getMetadataTableManager()->prePersist($metadata_table);
            } else {
                $this->getMetadataTableManager()->preUpdate($metadata_table);
            }
            $metadata_table->setMetadata($object);
            if ($metadata_table->getType() == 'query') {
                $query_fields = $this->getFieldsDescriptionsFromCustomQuery($metadata_table);
                $metadata_table->setCustomQueryFields($query_fields);
            } else {
                $metadata_table->setCustomQueryFields(null);
            }
        }

        $tables_keys = array_keys($tables);
        sort($tables_keys);
        $pos = 0;
        foreach ($tables_keys as $k) {
            $tables[$k]->setPosition($pos);
            $pos++;
        }
        //----------------------------------

        $relations = array();
        foreach ($object->getRelations() as $metadata_relation) {
            /* @var $metadata_relation MetadataRelation */
            $relations[$metadata_relation->getPosition()] = $metadata_relation;
            if (is_null($metadata_relation->getId())) {
                $this->getMetadataRelationManager()->prePersist($metadata_relation);
            } else {
                $this->getMetadataRelationManager()->preUpdate($metadata_relation);
            }
            $metadata_relation->setMetadata($object);
        }

        $relations_keys = array_keys($relations);
        sort($relations_keys);
        $pos = 0;
        foreach ($relations_keys as $k) {
            $relations[$k]->setPosition($pos);
            $pos++;
        }

        //----------------------------------

        $pos = 0;
        foreach ($tables_keys as $k) {
            $metadata_table = $tables[$k];
            /* @var $metadata_table MetadataTable */

            $columns = $this->getColumnsDescriptionsFromTable($metadata_table);

            // actualizar los campos que provienen del formulario del admin
            foreach ($object->getFields() as $metadata_field) {
                /* @var $metadata_field MetadataField */

                if ($metadata_field->getTable()->getId() == $metadata_table->getId()) {

                    if (isset($columns[$metadata_field->getName()])) { // si el campo esta entre los campos de la tabla o query
                        unset($columns[$metadata_field->getName()]);

                        $metadata_field->setMetadata($object);
                        $metadata_field->setPosition($pos);
                        if (is_null($metadata_field->getId())) {
                            $this->getMetadataFieldManager()->prePersist($metadata_field);
                        } else {
                            $this->getMetadataFieldManager()->preUpdate($metadata_field);
                        }
                        $pos++;
                    } else {
                        $object->removeField($metadata_field);
                    }
                }

            }
            // agregar los campos que no venian del admin, porque son nuevos en la tabla o query

            foreach ($columns as $column) {
                $new_field = $this->getMetadataFieldManager()->createNewInstance();
                $new_field->setMetadata($object);
                $new_field->setTable($metadata_table);
                $new_field->setName($column['name']);
                $new_field->setTitle($column['name']);
                $new_field->setType($column['type'] != null ? $column['type'] : 'string');
                $new_field->setEnabled(false);
                $new_field->setPosition($pos);
                $object->addField($new_field);
                $this->getMetadataFieldManager()->prePersist($new_field);
                $pos++;
            }
        }

    }

    //----------------------------------------------------------------------------------------------------------------

}