<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/05/2017
 * Time: 01:01
 */

namespace TechPromux\DynamicQueryBundle\Manager;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Pagerfanta;
use TechPromux\BaseBundle\Adapter\Paginator\DoctrineDbalPaginatorAdapter;
use TechPromux\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\DynamicQueryBundle\Entity\DataModel;
use TechPromux\DynamicQueryBundle\Entity\DataModelCondition;
use TechPromux\DynamicQueryBundle\Entity\DataModelDetail;
use TechPromux\DynamicQueryBundle\Entity\DataModelGroup;
use TechPromux\DynamicQueryBundle\Entity\DataModelOrder;
use TechPromux\DynamicQueryBundle\Entity\Metadata;
use TechPromux\DynamicQueryBundle\Entity\MetadataField;
use TechPromux\DynamicQueryBundle\Entity\MetadataTable;
use TechPromux\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;

class DataModelManager extends BaseResourceManager
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
     * Get entity class name
     *
     * @return class
     */
    public function getResourceClass()
    {
        return DataModel::class;
    }

    /**
     * Get entity short name
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'DataModel';
    }

    //--------------------------------------------------------------------------------

    /**
     * @var MetadataManager
     */
    protected $metadata_manager;

    /**
     * @var DataModelDetailManager
     */
    protected $datamodel_detail_manager;

    /**
     * @var DataModelGroupManager
     */
    protected $datamodel_group_manager;

    /**
     * @var DataModelConditionManager
     */
    protected $datamodel_condition_manager;

    /**
     * @var DataModelOrderManager
     */
    protected $datamodel_order_manager;

    /**
     * @var UtilDynamicQueryManager
     */
    protected $util_dynamic_query_manager;

    /**
     * @return MetadataManager
     */
    public function getMetadataManager()
    {
        return $this->metadata_manager;
    }

    /**
     * @param MetadataManager $metadata_manager
     * @return DataModelManager
     */
    public function setMetadataManager($metadata_manager)
    {
        $this->metadata_manager = $metadata_manager;
        return $this;
    }

    /**
     * @return DataModelDetailManager
     */
    public function getDatamodelDetailManager()
    {
        return $this->datamodel_detail_manager;
    }

    /**
     * @param DataModelDetailManager $datamodel_detail_manager
     * @return DataModelManager
     */
    public function setDatamodelDetailManager($datamodel_detail_manager)
    {
        $this->datamodel_detail_manager = $datamodel_detail_manager;
        return $this;
    }

    /**
     * @return DataModelGroupManager
     */
    public function getDatamodelGroupManager()
    {
        return $this->datamodel_group_manager;
    }

    /**
     * @param DataModelGroupManager $datamodel_group_manager
     * @return DataModelManager
     */
    public function setDatamodelGroupManager($datamodel_group_manager)
    {
        $this->datamodel_group_manager = $datamodel_group_manager;
        return $this;
    }

    /**
     * @return DataModelConditionManager
     */
    public function getDatamodelConditionManager()
    {
        return $this->datamodel_condition_manager;
    }

    /**
     * @param DataModelConditionManager $datamodel_condition_manager
     * @return DataModelManager
     */
    public function setDatamodelConditionManager($datamodel_condition_manager)
    {
        $this->datamodel_condition_manager = $datamodel_condition_manager;
        return $this;
    }

    /**
     * @return DataModelOrderManager
     */
    public function getDatamodelOrderManager()
    {
        return $this->datamodel_order_manager;
    }

    /**
     * @param DataModelOrderManager $datamodel_order_manager
     * @return DataModelManager
     */
    public function setDatamodelOrderManager($datamodel_order_manager)
    {
        $this->datamodel_order_manager = $datamodel_order_manager;
        return $this;
    }

    /**
     * @return UtilDynamicQueryManager
     */
    public function getUtilDynamicQueryManager()
    {
        return $this->util_dynamic_query_manager;
    }

    /**
     * @param UtilDynamicQueryManager $util_dynamic_query_manager
     * @return DataModelManager
     */
    public function setUtilDynamicQueryManager($util_dynamic_query_manager)
    {
        $this->util_dynamic_query_manager = $util_dynamic_query_manager;
        return $this;
    }

    //---------------------------------------------------------------------------------------

    /**
     * @param DataModel $object
     */
    public function postPersist($object)
    {

        parent::postPersist($object);

        $metadata = $object->getMetadata();
        /* @var $metadata Metadata */

        $i = 100000;
        foreach ($metadata->getFields() as $field) /* @var $field MetadataField */ {
            if ($field->getEnabled()) {
                $new_detail = $this->getDatamodelDetailManager()->createNewInstance();
                /* @var $new_detail DataModelDetail */
                $new_detail->setDatamodel($object);
                $new_detail->setField($field);
                $new_detail->setFunction('');

                $new_detail->setName($field->getName());
                $new_detail->setTitle($field->getTitle());
                $new_detail->setAbbreviation(strtoupper((str_split($field->getTitle() . ' ')[0])));
                $new_detail->setPresentationFormat('');
                $new_detail->setPresentationPrefix('');
                $new_detail->setPresentationSuffix('');

                $new_detail->setEnabled(true);
                $new_detail->setPosition($i++);

                $this->getDatamodelDetailManager()->persist($new_detail);
            }
        }
    }

    //-----------------------------------------------------------------------------------------

    /**
     * @param Collection $children
     * @param BaseResourceManager $manager
     */
    protected function updateChildrenPosition($children, $manager)
    {
        $positions = array();
        foreach ($children as $item) {
            $positions[$item->getPosition()] = $item;
            if (is_null($item->getId())) {
                $manager->prePersist($item);
            } else {
                $manager->preUpdate($item);
            }
        }
        $keys = array_keys($positions);
        sort($keys);
        $i = 100000;
        foreach ($keys as $k) {
            $item = $positions[$k];
            $item->setPosition($i);
            $i++;
        }
    }

    /**
     * @param DataModel $object
     */
    public function preUpdate($object)
    {
        parent::preUpdate($object);

        foreach ($object->getDetails() as $item) {
            $item->setDataModel($object);
        }
        foreach ($object->getGroups() as $item) {
            $item->setDataModel($object);
        }
        foreach ($object->getConditions() as $item) {
            $item->setDataModel($object);
        }
        foreach ($object->getOrders() as $item) {
            $item->setDataModel($object);
        }

        $this->updateChildrenPosition($object->getDetails(), $this->getDatamodelDetailManager());
        $this->updateChildrenPosition($object->getGroups(), $this->getDatamodelGroupManager());
        $this->updateChildrenPosition($object->getConditions(), $this->getDatamodelConditionManager());
        $this->updateChildrenPosition($object->getOrders(), $this->getDatamodelOrderManager());

    }

    //----------------------------------------------------------------------------------------------------

    /**
     *
     * @param DataModel $object
     * @return DataModel
     */
    public function createCopyForDataModel($object)
    {
        $datamodel = $this->find($object->getId());
        /* @var $dataModel DataModel */

        $duplicatedDataModel = $this->createNewInstance();
        /* @var $duplicatedDataModel DataModel */

        $duplicatedDataModel->setMetadata($datamodel->getMetadata());

        $duplicatedDataModel->setName($datamodel->getName() . ' (' . $this->trans('Duplicated') . ')');
        $duplicatedDataModel->setTitle($datamodel->getTitle() . ' (' . $this->trans('Duplicated') . ')');

        $duplicatedDataModel->setDescription($datamodel->getDescription());
        $duplicatedDataModel->setEnabled(false);

        //-------------------------------------------------

        $details = $this->getDatamodelDetailManager()->findBy(array('datamodel' => $datamodel->getId()));

        foreach ($details as $dtl) {
            /* @var $dtl DataModelDetail */
            $duplicatedDtl = $this->getDatamodelDetailManager()->createNewInstance();
            /* @var $duplicatedDtl DataModelDetail */

            $duplicatedDtl->setDatamodel($duplicatedDataModel);

            $duplicatedDtl->setName($dtl->getName());
            $duplicatedDtl->setTitle($dtl->getTitle());

            $duplicatedDtl->setField($dtl->getField());
            $duplicatedDtl->setFunction($dtl->getFunction());

            $duplicatedDtl->setAbbreviation($dtl->getAbbreviation());

            $duplicatedDtl->setPresentationFormat($dtl->getPresentationFormat());
            $duplicatedDtl->setPresentationPrefix($dtl->getPresentationPrefix());
            $duplicatedDtl->setPresentationSuffix($dtl->getPresentationSuffix());

            $duplicatedDtl->setEnabled($dtl->getEnabled());
            $duplicatedDtl->setPosition($dtl->getPosition());

            $this->getDatamodelDetailManager()->prePersist($duplicatedDtl);

            $duplicatedDataModel->addDetail($duplicatedDtl);

            //$this->getEntityManager()->persist($duplicatedDtl);
        }

        //-------------------------------------------------

        $conditions = $this->getDatamodelConditionManager()->findBy(array('datamodel' => $datamodel->getId()));

        foreach ($conditions as $cdtn) {
            /* @var $cdtn DataModelCondition */
            $duplicatedCdtn = $this->getDatamodelConditionManager()->createNewInstance();
            /* @var $duplicatedCdtn DataModelCondition */

            $duplicatedCdtn->setDatamodel($duplicatedDataModel);

            $duplicatedCdtn->setName($cdtn->getName());
            $duplicatedCdtn->setTitle($cdtn->getTitle());

            $duplicatedCdtn->setField($cdtn->getField());
            $duplicatedCdtn->setFunction($cdtn->getFunction());

            $duplicatedCdtn->setOperator($cdtn->getOperator());

            $duplicatedCdtn->setCompareToType($cdtn->getCompareToType());
            $duplicatedCdtn->setCompareToFixedValue($cdtn->getCompareToFixedValue());
            $duplicatedCdtn->setCompareToDynamicValue($cdtn->getCompareToDynamicValue());
            $duplicatedCdtn->setCompareToField($cdtn->getCompareToField());
            $duplicatedCdtn->setCompareToFunction($cdtn->getCompareToFunction());

            $duplicatedCdtn->setEnabled($cdtn->getEnabled());
            $duplicatedCdtn->setPosition($cdtn->getPosition());

            $this->getDatamodelConditionManager()->prePersist($duplicatedCdtn);

            $duplicatedDataModel->addCondition($duplicatedCdtn);
        }

        //-------------------------------------------------

        $groups = $this->getDatamodelGroupManager()->findBy(array('datamodel' => $datamodel->getId()));

        foreach ($groups as $grp) {
            /* @var $grp DataModelGroup */
            $duplicatedGrp = $this->getDatamodelGroupManager()->createNewInstance();
            /* @var $duplicatedGrp DataModelGroup */

            $duplicatedGrp->setDatamodel($duplicatedDataModel);

            $duplicatedGrp->setName($dtl->getName());
            $duplicatedGrp->setTitle($dtl->getTitle());

            $duplicatedGrp->setField($dtl->getField());
            $duplicatedGrp->setFunction($dtl->getFunction());

            $duplicatedGrp->setEnabled($dtl->getEnabled());
            $duplicatedGrp->setPosition($dtl->getPosition());

            $this->getDatamodelGroupManager()->prePersist($duplicatedGrp);

            $duplicatedDataModel->addGroup($duplicatedGrp);
        }

        //-------------------------------------------------

        $orders = $this->getDatamodelOrderManager()->findBy(array('datamodel' => $datamodel->getId()));

        foreach ($orders as $ord) {
            /* @var $ord DataModelOrder */
            $duplicatedOrd = $this->getDatamodelOrderManager()->createNewInstance();
            /* @var $duplicatedOrd DataModelOrder */

            $duplicatedOrd->setDatamodel($duplicatedDataModel);

            $duplicatedOrd->setName($ord->getName());
            $duplicatedOrd->setTitle($ord->getTitle());

            $duplicatedOrd->setField($ord->getField());
            $duplicatedOrd->setFunction($ord->getFunction());
            $duplicatedOrd->setType($ord->getType());

            $duplicatedOrd->setEnabled($ord->getEnabled());
            $duplicatedOrd->setPosition($ord->getPosition());

            $this->getDatamodelOrderManager()->prePersist($duplicatedOrd);

            $duplicatedDataModel->addOrder($duplicatedOrd);
        }

        //-------------------------------------------------

        $duplicatedDataModel = $this->persist($duplicatedDataModel);

        return $duplicatedDataModel;
    }

    //----------------------------------------------------------------------------------------------------------

    /**
     *
     * @param DataModel $datamodel
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getQueryBuilderFromDataModel(DataModel $datamodel)
    {
        /* @var $metadata Metadata */
        $metadata = $datamodel->getMetadata();
        /* @var $queryBuilder \Doctrine\DBAL\Query\QueryBuilder */
        $queryBuilder = $this->getMetadataManager()->getQueryBuilderFromMetadata($metadata);

        // QUERY PARTS FILL PROCCESS

        $added_details_sql_names_aliasses = array();

        // ADD SELECT SQL PART

        foreach ($datamodel->getDetails() as $detail) {
            /* @var $detail DataModelDetail */
            if ($detail->getEnabled()) {
                $detail_sql_name = $detail->getSqlName();
                $detail_sql_alias = $detail->getSQLAlias();
                $queryBuilder->addSelect($detail_sql_name . ' AS ' . $detail_sql_alias);

                $added_details_sql_names_aliasses[$detail_sql_name] = $detail_sql_alias;
            }

        }

        // ADD GROUP BY SQL PART

        foreach ($datamodel->getGroups() as $group) {
            /* @var $group DataModelGroup */
            if ($group->getEnabled()) {
                $group_sql_name = $group->getSqlName();
                if (isset($added_details_sql_names_aliasses[$group_sql_name]))
                    $group_sql_name = $added_details_sql_names_aliasses[$group_sql_name]; // get detail alias
                $queryBuilder->addGroupBy($group_sql_name);
            }
        }

        // ADD ORDER BY SQL PART

        foreach ($datamodel->getOrders() as $order) {
            /* @var $order DataModelOrder */
            if ($order->getEnabled()) {
                $order_sql_name = $order->getSqlName();
                if (isset($added_details_sql_names_aliasses[$order_sql_name]))
                    $order_sql_name = $added_details_sql_names_aliasses[$order_sql_name]; // get detail alias
                $type = $order->getType();
                $queryBuilder->addOrderBy($order_sql_name, $type);
            }
        }

        // ADD WHERE AND HAVING SQL PARTS

        $operators = $this->getUtilDynamicQueryManager()->getRegisteredConditionalOperators();

        $dynamic_values_options = $this->getUtilDynamicQueryManager()->getRegisteredDynamicValues();

        foreach ($datamodel->getConditions() as $condition) {
            /* @var $condition DataModelCondition */

            if ($condition->getEnabled()) {
                $in_select_clause = true;

                // Preparing left side of condition

                $condition_left_sql_name = $condition->getSqlName();

                $left_function_name = $condition->getFunction();

                $left_function = $this->getUtilDynamicQueryManager()->getFieldFunctionById($left_function_name);

                $left_operand = $condition_left_sql_name;

                if ($left_function != null && $left_function->getHasAggregation()) {
                    $in_select_clause = false;
                }

                // Preparing operator of condition

                $operator_name = $condition->getOperator();

                $operator_selected = $operators[$operator_name];
                /* @var $operator_selected BaseConditionalOperatorType */

                $right_operand = null;

                if (!$operator_selected->getIsUnary()) {

                    switch ($condition->getCompareToType()) {
                        case 'FIXED':
                            $value = $condition->getCompareToFixedValue();
                            $param_name = null;

                            if ($operator_selected->getIsForRightOperandAsArray()) {
                                $param_name = array();
                                foreach (explode(',', $value) as $v) {
                                    $param_name[] = $queryBuilder->createNamedParameter(trim($v));
                                }
                            } else {
                                $param_name = $queryBuilder->createNamedParameter($value);
                            }

                            $right_operand = $param_name;
                            break;
                        case 'DYNAMIC':

                            $dynamic_value_id = $condition->getCompareToDynamicValue();

                            $dynamic_value_selected = $this->getUtilDynamicQueryManager()->getDynamicValueById($dynamic_value_id);

                            $dynamic_value = $dynamic_value_selected->getDynamicValue();

                            $param_name = $queryBuilder->createNamedParameter($dynamic_value);

                            $right_function_name = $condition->getCompareToFunction();

                            $right_function = $this->getUtilDynamicQueryManager()->getFieldFunctionById($right_function_name);

                            if ($right_function != null) {
                                $right_operand = $right_function->getAppliedFunctionToField($param_name);

                                if ($right_function->getHasAggregation()) {
                                    $in_select_clause = false;
                                }

                            } else {
                                $right_operand = $param_name;
                            }


                            break;
                        case 'FIELD':

                            $right_field_name = $condition->getCompareToField()->getSQLName();

                            $right_function_name = $condition->getCompareToFunction();

                            $right_function = $this->getUtilDynamicQueryManager()->getFieldFunctionById($right_function_name);

                            if ($right_function != null) {
                                $right_operand = $right_function->getAppliedFunctionToField($right_field_name);

                                if ($right_function->getHasAggregation()) {
                                    $in_select_clause = false;
                                }

                            } else {
                                $right_operand = $right_field_name;
                            }

                            break;
                    }

                }

                if (!$in_select_clause) {
                    $left_operand = isset($added_details_sql_names_aliasses[$left_operand]) ? $added_details_sql_names_aliasses[$left_operand] : $left_operand;
                    $right_operand = isset($added_details_sql_names_aliasses[$right_operand]) ? $added_details_sql_names_aliasses[$right_operand] : $right_operand;
                }

                $str_condition = $operator_selected->getConditionalStatement($left_operand, $right_operand);

                if ($in_select_clause)
                    $queryBuilder->andWhere($str_condition);
                else
                    $queryBuilder->andHaving($str_condition);

            }

        }

        return $queryBuilder;
    }

    /**
     *
     * @param DataModel $datamodel
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getQueryBuilderFromDataModelDetail(DataModelDetail $detail)
    {
        /* @var $metadata_table MetadataTable */
        $metadata_table = $detail->getField()->getTable();

        /* @var $queryBuilder \Doctrine\DBAL\Query\QueryBuilder */
        $queryBuilder = $this->getMetadataManager()->getQueryBuilderFromMetadataTable($metadata_table);

        $detail_sql_name = $detail->getSqlName();
        $detail_sql_alias = $detail->getSQLAlias();

        $queryBuilder->addSelect('DISTINCT ' . $detail_sql_name . ' AS ' . $detail_sql_alias);

        return $queryBuilder;
    }

    /**
     * @param DataModelDetail $detail
     * @return array
     */
    public function getDistinctResultsFromDatamodelDetail(DataModelDetail $detail)
    {
        $queryBuilder = $this->getQueryBuilderFromDataModelDetail($detail);

        $result = $queryBuilder->execute()->fetchAll();

        return $result;
    }

    /**
     * @param DataModelDetail $detail
     * @return array
     */
    public function getDistinctResultsChoicesFromDatamodelDetail(DataModelDetail $detail)
    {
        $result = $this->getDistinctResultsFromDatamodelDetail($detail);

        $alias = $detail->getSQLAlias();

        $result_choices = array();

        foreach ($result as $i => $row) {
            $result_choices[$row[$alias]] = $row[$alias];
        }

        return $result_choices;
    }

    //---------------------------------------------------------------------------------------------------------------

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filter_by
     * @param array $order_by
     * @return QueryBuilder
     */
    public function appendFilters($queryBuilder, $filter_by = array(), $order_by = array())
    {

        // TODO tener en cuenta los parametros de tipo array

        // ADD FILTER OPTIONS

        foreach ($filter_by as $fb) {

            $datamodel_detail = $this->getDatamodelDetailManager()->find($fb['id']);
            /* @var $datamodel_detail DataModelDetail */

            $detail_function = $this->getUtilDynamicQueryManager()->getFieldFunctionById($datamodel_detail->getFunction());

            $left_operand = '';

            if (!$detail_function->getHasAggregation()) {
                $left_operand = $datamodel_detail->getSqlName();
            } else {
                $left_operand = $datamodel_detail->getSQLAlias();
            }

            $operator_selected = $this->getUtilDynamicQueryManager()->getConditionalOperatorById($fb['operator']);

            $right_operand = null;

            if (!$operator_selected->getIsUnary()) {
                if ($operator_selected->getIsForRightOperandAsArray()) {
                    $right_operand = array();
                    foreach (explode(',', isset($fb['value']) ? $fb['value'] : '') as $v) {
                        $right_operand[] = $queryBuilder->createNamedParameter(trim($v));
                    }
                } else {
                    $right_operand = $queryBuilder->createNamedParameter(isset($fb['value']) ? $fb['value'] : null);
                }
            }

            $str_condition = $operator_selected->getConditionalStatement($left_operand, $right_operand);

            if (!$detail_function->getHasAggregation()) {
                $queryBuilder->andWhere($str_condition);
            } else {
                $queryBuilder->andHaving($str_condition);
            }
        }

        // ADD ORDER OPTIONS

        $orders = $queryBuilder->getQueryPart('orderBy');
        $queryBuilder->resetQueryPart('orderBy');

        foreach ($order_by as $ob) {
            $datamodel_detail = $this->getDatamodelDetailManager()->find($ob['id']);
            $detail_sql_alias = $datamodel_detail->getSQLAlias();
            $order = $ob['type'];
            $queryBuilder->addOrderBy($detail_sql_alias, $order);
        }
        foreach ($orders as $or) {
            $parts = explode(' ', $or);
            $queryBuilder->addOrderBy($parts[0], $parts[1]);
        }

        return $queryBuilder;
    }

    //----------------------------------------------------------------------------

    public function getSQLFromDataModel($datamodel_id)
    {
        $queryBuilder = $this->getQueryBuilderFromDataModel($datamodel_id);
        return $queryBuilder->getSQL();
    }

    public function getResultFromDataModel($datamodel_id)
    {
        $queryBuilder = $this->getQueryBuilderFromDataModel($datamodel_id);
        return $queryBuilder->execute()->fetchAll();
    }

    public function getResultRowCountFromDataModel($datamodel_id)
    {
        $queryBuilder = $this->getQueryBuilderFromDataModel($datamodel_id);
        return $queryBuilder->execute()->rowCount();
    }

    //---------------------------------------------------------------------------------------------

    public function getEnabledDetailsDescriptionsFromDataModel($datamodel_id)
    {

        $details = $this->getDatamodelDetailManager()->findBy(array('datamodel' => $datamodel_id, 'enabled' => true), array('position' => 'ASC'));

        $descriptions = array();

        foreach ($details as $detail) {
            /* @var $detail DataModelDetail */
            //  if ($detail->getEnabled()) {

            $descriptions[$detail->getId()] = array(
                'id' => $detail->getId(),
                'name' => $detail->getName(),
                'title' => $detail->getTitle(),
                'abbreviation' => $detail->getAbbreviation(),
                'alias' => $detail->getSQLAlias(),
                'format' => $detail->getPresentationFormat(),
                'prefix' => $detail->getPresentationPrefix(),
                'suffix' => $detail->getPresentationSuffix(),
                'type' => $type = $this->getUtilDynamicQueryManager()->getFieldFunctionSQLType($detail->getField(), $detail->getFunction()),
                'classification' => $this->getUtilDynamicQueryManager()->getIsNumberDatetimeOrString($type),
            );
            // }
        }

        return $descriptions;
    }

    //--------------------------------------------------------------------------------------------------------

    // TODO: Pasar para el controller, esto es de la vista, el manager solo se encarga de generar el query
    // asi como los otros metodos para las runnables query....

    protected function createPaginatorAdapterForQueryBuilder($queryBuilder)
    {
        return new DoctrineDbalPaginatorAdapter($queryBuilder);
    }

    /**
     * @param $queryBuilder
     * @return Pagerfanta
     */
    public function createPaginatorForQueryBuilder($queryBuilder)
    {
        $adapter = $this->createPaginatorAdapterForQueryBuilder($queryBuilder);

        $paginator = new Pagerfanta($adapter);

        return $paginator;
    }

}