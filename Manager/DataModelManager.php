<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/05/2017
 * Time: 01:01
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Manager;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Pagerfanta;
use TechPromux\Bundle\BaseBundle\Manager\Adapter\Paginator\DoctrineDbalPaginatorAdapter;
use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelCondition;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelDetail;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelGroup;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelOrder;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataSource;
use TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation;
use TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable;
use TechPromux\Bundle\DynamicQueryBundle\Type\ConditionalOperator\BaseConditionalOperatorType;

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
     * Obtiene la clase de la entidad
     *
     * @return class
     */
    public function getResourceClass()
    {
        return DataModel::class;
    }

    /**
     * Obtiene el nombre corto de la entidad
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
     * @var DynamicQueryUtilManager
     */
    protected $util_manager;

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
    public function createCpyForDataModel($object)
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

            //$this->getDoctrineEntityManager()->persist($duplicatedDtl);
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
    public function getQueryBuilderFromDataModel($datamodel)
    {
        /* @var $metadata \TechPrommux\DynamicQueryBundle\Entity\Metadata */
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

        $operators = $this->getDynamicQueryUtilManager()->getRegisteredConditionalOperators();

        $dynamic_values_options = $this->getDynamicQueryUtilManager()->getRegisteredComparablesDynamicValues();

        foreach ($datamodel->getConditions() as $condition) {
            /* @var $condition DataModelCondition */

            if ($condition->getEnabled()) {
                $in_select_clause = true;

                // Preparing left side of condition

                $condition_left_sql_name = $condition->getSqlName();

                $left_function_name = $condition->getFunction();

                $left_function = $this->getDynamicQueryUtilManager()->getFieldFunctionById($left_function_name);

                $left_operand = $condition_left_sql_name;

                if ($left_function->getHasAggregation()) {
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

                            $dynamic_value_selected_options = $dynamic_values_options['values'][$dynamic_value_id];

                            $dynamic_value = call_user_func($dynamic_value_selected_options['function']);

                            $param_name = $queryBuilder->createNamedParameter($dynamic_value);

                            $right_function_name = $condition->getCompareToFunction();

                            $right_function = $this->getDynamicQueryUtilManager()->getFieldFunctionById($right_function_name);

                            $right_operand = $right_function->getAppliedFunctionToField($param_name);

                            if ($right_function->getHasAggregation()) {
                                $in_select_clause = false;
                            }

                            break;
                        case 'FIELD':

                            $right_field_name = $condition->getCompareToField()->getSQLName();

                            $right_function_name = $condition->getCompareToFunction();

                            $right_function = $this->getDynamicQueryUtilManager()->getFieldFunctionById($right_function_name);

                            $right_operand = $right_function->getAppliedFunctionToField($right_field_name);

                            if ($right_function->getHasAggregation()) {
                                $in_select_clause = false;
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

    //---------------------------------------------------------------------------------------------------------------

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filter_by
     * @param array $order_by
     * @return QueryBuilder
     */
    public function appendFilters($queryBuilder, $filter_by = array(), $order_by = array())
    {
        // ADD FILTER OPTIONS

        foreach ($filter_by as $fb) {

            $datamodel_detail = $this->getDatamodelDetailManager()->find($fb['id']);
            /* @var $datamodel_detail DataModelDetail */

            $detail_function = $this->getDynamicQueryUtilManager()->getFieldFunctionById($datamodel_detail->getFunction());

            $left_operand = '';

            if (!$detail_function->getHasAggregation()) {
                $left_operand = $datamodel_detail->getSqlName();
            } else {
                $left_operand = $datamodel_detail->getSQLAlias();
            }

            $operator_selected = $this->getDynamicQueryUtilManager()->getConditionalOperatorById($fb['operator']);

            $right_operand = null;

            if (!$operator_selected->getIsUnary()) {
                $right_operand = $queryBuilder->createNamedParameter(isset($fb['value']) ? $fb['value'] : null);
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
           // $queryBuilder->add('orderBy', $or);
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
                'type' => $type = $this->getDynamicQueryUtilManager()->getFieldFunctionSQLType($detail->getField(), $detail->getFunction()),
                'classification' => $this->getDynamicQueryUtilManager()->getIsNumberDatetimeOrString($type),
            );
            // }
        }

        return $descriptions;
    }

    //--------------------------------------------------------------------------------------------------------

    // TODO: ver para donde pasar esto que es de la vista

    /**
     * @return \Symfony\Component\Form\FormBuilder
     * @param DataModel $datamodel
     */
    public function createFilterFormFromDataModel(DataModel $datamodel)
    {

        $filters_form = $this->createFormBuilder(null, array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering'),
            'widget_prefix' => 'filters_form_' . $datamodel->getId(),
        ));

        $details = $this->getDatamodelDetailManager()
            ->findBy(array('datamodel' => $datamodel->getId(), 'enabled' => true), array('position' => 'ASC'));

        $input_types_for_attr_types = array(
            'guid' => 'text',
            'string' => 'text',
            'text' => 'text',
            'boolean' => 'integer',
            'integer' => 'integer',
            'smallint' => 'integer',
            'bigint' => 'integer',
            'decimal' => 'number',
            'float' => 'number',
            'datetime' => 'datetime',
            'date' => 'date',
            'time' => 'time',
        );

        $i = 0;
        foreach ($details as $detail) {
            /* @var $detail DataModelDetail */

            $field_label = $detail->getTitle();

            $conditional_operators = $this->getDynamicQueryUtilManager()->getConditionalOperatorsChoices($detail->getSqlType());

            for ($j = 0; $j <= 1; $j++) {
                $filters_form->add("d_" . ($i * 2 + $j) . "_id", 'hidden', array(
                    'label' => $j == 0 ? $field_label : ' ',
                    'required' => false,
                    'empty_data' => $detail->getId(),
                    'attr' => array('value' => $detail->getId())
                ))
                    ->add("d_" . ($i * 2 + $j) . "_operator", 'choice', array(
                        'label' => false,
                        'required' => false,
                        'choices' => $conditional_operators,
                        'multiple' => false,
                        'expanded' => false
                    ));

                if ($detail->getSqlTypeCategorization() == 'datetime') {
                    $filters_form->add("d_" . ($i * 2 + $j) . "_value", $input_types_for_attr_types[$detail->getSqlType()], array(
                        'label' => false,
                        'required' => false,
                        'widget' => 'single_text',
                        'attr' => array('class' => $detail->getSqlType(), 'type' => $detail->getSqlType())
                    ));
                } else {
                    $filters_form->add("d_" . ($i * 2 + $j) . "_value", $input_types_for_attr_types[$detail->getSqlType()], array(
                        'label' => false,
                        'required' => false,
                    ));
                }
            }
            $i++;
        }

        $filters_form->add("_filters_count", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => $i * 2,
            'attr' => array('value' => $i * 2)
        ));

        $filters_form->add("_sort_by", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => '',
            'attr' => array('value' => '')
        ));
        $filters_form->add("_sort_order", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => '',
            'attr' => array('value' => '')
        ));
        $filters_form->add("_page", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => 1,
        ));
        $filters_form->add("_items_per_page", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => 32,
        ));

        return $filters_form->getForm();
    }

    //-------------------------------------------------------------------------------------------

    /**
     * @param DataModel $datamodel
     * @param array $filter_data
     * @return array
     */
    public function getFiltersValuesFromFiltersFormData($datamodel, $filter_data)
    {
        $_filters_count = (int)$filter_data['_filters_count'];

        $filter_by = array();

        for ($i = 0; $i < $_filters_count; $i++) {

            $detail_id = $filter_data['d_' . $i . '_id'];

            $detail = $this->getDatamodelDetailManager()
                ->findOneBy(array('id' => $detail_id, 'datamodel' => $datamodel->getId(), 'enabled' => true), array('position' => 'ASC'));
            /* @var $detail DataModelDetail */

            $operator = $filter_data['d_' . $i . '_operator'];

            $value = $filter_data['d_' . $i . '_value'];

            $operator_selected = $this->getDynamicQueryUtilManager()->getConditionalOperatorById($operator);

            if ($operator_selected != null) {
                if (!$operator_selected->getIsUnary() || ($value != null && $value != '')) {
                    if ($detail->getSqlType() == 'date') {
                        $value = $value->format('Y-m-d');
                    } else if ($detail->getSqlType() == 'time') {
                        $value = $value->format('H:i:s');
                    } else if ($detail->getSqlType() == 'datetime') {
                        $value = $value->format('Y-m-d H:i:s');
                    }
                }
                $filter_by[] = array(
                    'id' => $detail_id,
                    'operator' => $operator,
                    'value' => "".$value,
                );
            }
        }

        return $filter_by;
    }

    /**
     * @param DataModel $datamodel
     * @param array $filter_data
     * @return array
     */
    public function getOrdersValuesFromFiltersFormData(DataModel $datamodel, $filter_data)
    {

        $order_by = array();

        if (!empty($filter_data['_sort_by'])) {
            $order_by[] = array(
                'id' => $filter_data['_sort_by'],
                'type' => isset($filter_data['_sort_order']) ? $filter_data['_sort_order'] : 'ASC'
            );
        }

        return $order_by;
    }

    //------------------------------------------------------------------------------------------------------------

    // TODO: Pasar para el controller, esto es de la vista, el manager solo se encarga de generar el query
    // asi como los otros metodos para las runnables query....

    public function createPaginatorAdapterForQueryBuilder($queryBuilder)
    {
        return new DoctrineDbalPaginatorAdapter($queryBuilder);
    }

    public function createPaginatorForQueryBuilder($queryBuilder)
    {
        $adapter = $this->createPaginatorAdapterForQueryBuilder($queryBuilder);
        return new Pagerfanta($adapter);
    }

    public function paginatorFromQueryBuilder($queryBuilder, \Symfony\Component\HttpFoundation\Request $request)
    {

        $paginator = $this->createPaginatorForQueryBuilder($queryBuilder, $request);

        $paginator->setMaxPerPage($request->get('items_per_page', 32));

        $page = $request->get('page', 1);

        $rowCount = $queryBuilder->execute()->rowCount();

        if (intval($page) * $paginator->getMaxPerPage() <= $rowCount || ((intval($page) - 1) * $paginator->getMaxPerPage() < $rowCount)) {
            $paginator->setCurrentPage($page);
        } else {
            $paginator->setCurrentPage(1);
        }

        return $paginator;
    }


}