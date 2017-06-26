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
            if ($field->getSelected()) {
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

                $new_detail->setIsGroupedBy(false);
                $new_detail->setIsPublic(true);
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
    public function copyDataModel($object)
    {

        throw new \Exception('2'); // revisar $datamodel por datamodel

        $datamodel = $this->findById($object->getId());

        $duplicatedQuery = $this->createNewEntity();

        $duplicatedQuery->setMetadata($datamodel->getMetadata());
        $duplicatedQuery->setTitle($datamodel->getTitle() . ' (' . $this->trans('Duplicated') . ')');
        $duplicatedQuery->setDescription($datamodel->getDescription());
        $duplicatedQuery->setEnabled(false);
        $duplicatedQuery->setCode($datamodel->getCode() . '__DUPLICATED__' . (new \DateTime())->format('YmdHis'));

        //-------------------------------------------------

        $details = $this->getDatamodelDetailManager()->findBy(array('datamodel' => $datamodel->getId()));

        foreach ($details as $dtl) {
            $duplicatedDtl = $this->createNewDataModelDetailEntity();

            $duplicatedDtl->setQuery($duplicatedQuery);
            $duplicatedDtl->setField($dtl->getField());

            $duplicatedDtl->setTitle($dtl->getTitle());
            $duplicatedDtl->setAbreviature($dtl->getAbreviature());
            $duplicatedDtl->setFunction($dtl->getFunction());
            $duplicatedDtl->setFormat($dtl->getFormat());
            $duplicatedDtl->setPrefix($dtl->getPrefix());
            $duplicatedDtl->setSuffix($dtl->getSuffix());
            $duplicatedDtl->setIsPublic($dtl->getIsPublic());
            $duplicatedDtl->setIsGroup($dtl->getIsGroup());
            $duplicatedDtl->setPosition($dtl->getPosition());

            parent::prePersist($duplicatedDtl);
            $duplicatedQuery->addDetail($duplicatedDtl);
            $this->getDoctrineEntityManager()->persist($duplicatedDtl);
        }

        //-------------------------------------------------

        $groups = $this->findDataModelDetailByQueryId($datamodel->getId());

        foreach ($details as $dtl) {
            $duplicatedDtl = $this->createNewDataModelDetailEntity();

            $duplicatedDtl->setQuery($duplicatedQuery);
            $duplicatedDtl->setField($dtl->getField());

            $duplicatedDtl->setTitle($dtl->getTitle());
            $duplicatedDtl->setAbreviature($dtl->getAbreviature());
            $duplicatedDtl->setFunction($dtl->getFunction());
            $duplicatedDtl->setFormat($dtl->getFormat());
            $duplicatedDtl->setPrefix($dtl->getPrefix());
            $duplicatedDtl->setSuffix($dtl->getSuffix());
            $duplicatedDtl->setIsPublic($dtl->getIsPublic());
            $duplicatedDtl->setIsGroup($dtl->getIsGroup());
            $duplicatedDtl->setPosition($dtl->getPosition());

            parent::prePersist($duplicatedDtl);
            $duplicatedQuery->addDetail($duplicatedDtl);
            $this->getDoctrineEntityManager()->persist($duplicatedDtl);
        }

        //-------------------------------------------------

        $conditions = $this->findDataModelDetailConditionByQueryId($datamodel->getId());

        foreach ($conditions as $cdtn) {
            $duplicatedCdtn = $this->createNewDataModelDetailConditionEntity();

            $duplicatedCdtn->setQuery($duplicatedQuery);
            $duplicatedCdtn->setDetail($details_to_duplicate[$cdtn->getDetail()->getId()]); ///// ?????

            $duplicatedCdtn->setOperator($cdtn->getOperator());
            $duplicatedCdtn->setValueType($cdtn->getValueType());
            $duplicatedCdtn->setFixedValueToCompare($cdtn->getFixedValueToCompare());
            $duplicatedCdtn->setDynamicValueToCompare($cdtn->getDynamicValueToCompare());
            $duplicatedCdtn->setPosition($cdtn->getPosition());

            parent::prePersist($duplicatedCdtn);
            $duplicatedQuery->addCondition($duplicatedCdtn);
            $this->getDoctrineEntityManager()->persist($duplicatedCdtn);
        }

        //-------------------------------------------------

        $sorts = $this->findDataModelDetailSortByQueryId($datamodel->getId());

        foreach ($sorts as $srt) {
            $duplicatedSrt = $this->createNewDataModelDetailSortEntity();

            $duplicatedSrt->setQuery($duplicatedQuery);
            $duplicatedSrt->setDetail($details_to_duplicate[$srt->getDetail()->getId()]); ///// ?????

            $duplicatedSrt->setType($srt->getType());
            $duplicatedSrt->setPosition($srt->getPosition());

            parent::prePersist($duplicatedSrt);
            $duplicatedQuery->addSort($duplicatedSrt);
            $this->getDoctrineEntityManager()->persist($duplicatedSrt);
        }

        parent::prePersist($duplicatedQuery);
        $this->getDoctrineEntityManager()->persist($duplicatedQuery);
        $this->getDoctrineEntityManager()->flush($duplicatedQuery);

        return $duplicatedQuery;
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

        // DETAILS: SELECT PART

        foreach ($datamodel->getDetails() as $detail) {
            /* @var $detail DataModelDetail */
            if ($detail->getEnabled()) {
                $detail_sql_name = $detail->getSqlName();
                $detail_sql_alias = $detail->getSQLAlias();
                $queryBuilder->addSelect($detail_sql_name . ' AS ' . $detail_sql_alias);

                $added_details_sql_names_aliasses[$detail_sql_name] = $detail_sql_alias;
            }

        }

        foreach ($datamodel->getGroups() as $group) {
            /* @var $group DataModelGroup */
            if ($group->getEnabled()) {
                $group_sql_name = $group->getSqlName();
                if (isset($added_details_sql_names_aliasses[$group_sql_name]))
                    $group_sql_name = $added_details_sql_names_aliasses[$group_sql_name]; // get detail alias
                $queryBuilder->addGroupBy($group_sql_name);
            }
        }

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

        // CONDITIONS: WHERE AND HAVING PARTS

        $operators_options = $this->getDynamicQueryUtilManager()->getRegisteredConditionalOperators();
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

                $operator_selected_options = $operators_options['operators'][$operator_name];

                $right_operand = null;

                if ($operator_selected_options['type'] == 'BINARY') {

                    switch ($condition->getCompareToType()) {
                        case 'FIXED':
                            $value = $condition->getCompareToFixedValue();
                            $param_name = null;

                            // if is multiple value???? y si $value es string y no array,
                            // de igual manera si no es multiple y el value es un array???

                            if ($operator_name == 'BINARY.IN') {
                                $param_name = array();
                                foreach (explode(',', $value) as $v) {
                                    $param_name[] = $queryBuilder->createNamedParameter($v);
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

                            // segun el operador, ver si es multiple enotnces el param_name hay que convertirlo a array

                            $param_name = $queryBuilder->createNamedParameter($dynamic_value);

                            $right_function_name = $condition->getCompareToFunction();

                            $right_function = $this->getDynamicQueryUtilManager()->getFieldFunctionById($right_function_name);

                            // si aqui viene un param_name en forma de array y la funcion no soporta le array?

                            $right_operand = $right_function->getAppliedFunctionToField($param_name);

                            if ($right_function->getHasAggregation()) {
                                $in_select_clause = false;
                            }

                            break;
                        case 'FIELD':

                            $right_field_name = $condition->getCompareToField()->getSQLName();

                            $right_function_name = $condition->getCompareToFunction();

                            $right_function = $this->getDynamicQueryUtilManager()->getFieldFunctionById($right_function_name);

                            // aqui nunca vendra un array de param_name, pues es el nombre de un field

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

                $str_condition = call_user_func($operator_selected_options['function'], $left_operand, $right_operand);

                if ($in_select_clause)
                    $queryBuilder->andWhere($str_condition);
                else
                    $queryBuilder->andHaving($str_condition);

            }

        }

        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filter_by
     * @param array $order_by
     * @return QueryBuilder
     */
    public function appendFilters($queryBuilder, $filter_by = array(), $order_by = array())
    {
        // ADD FILTER OPTIONS

        // en el filter by, no es necesario el id del detail, solo el alias SQL, el operator id y el value, con eso
        // se crea la sentencia y se inserta en el having??? solo en el having????

        $operators_options = $this->getDynamicQueryUtilManager()->getRegisteredConditionalOperators();

        foreach ($filter_by as $fb) {
            $datamodel_detail = $this->getDatamodelDetailManager()->find($fb['detail_id']);
            $detail_sql_alias = $datamodel_detail->getSQLAlias();

            $operator_selected_options = $operators_options['operators'][$fb['operator']];


            $left_operand = $detail_sql_alias;
            $right_operand = $queryBuilder->createNamedParameter(isset($fb['value']) ? $fb['value'] : null);

            $str_condition = call_user_func($operator_selected_options['function'], $left_operand, $right_operand);

            $queryBuilder->andHaving($str_condition);
        }

        // ADD ORDER OPTIONS

        // las orders, solo se necesita el alias sql y el order (pedir las orders que ya tiene, resetearlas, pasar primero estas
        // y luego setear las que ya tenia)

        foreach ($order_by as $ob) {
            $datamodel_detail = $this->getDatamodelDetailManager()->find($ob['detail_id']);
            $detail_sql_alias = $datamodel_detail->getSQLAlias();
            $order = $ob['order_asc_desc'];
            $queryBuilder->addOrderBy($detail_sql_alias, $order);
        }

        return $queryBuilder;
    }

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

    // TODO: ver para donde pasar esto que es de la vista

    /**
     * @return \Symfony\Component\Form\FormBuilder
     * @param DataModel $datamodel
     */
    public function createFiltersFormForQuery(DataModel $datamodel)
    {

        $filters_form = $this->createFormBuilder(null, array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering'),
            'widget_prefix' => 'filters_form_' . $datamodel->getId(),
        ));

        $details_for_filter = $this->getDatamodelDetailManager()->descriptionForPublicDetailsFromQuery($datamodel);

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
        foreach ($details_for_filter as $detail) {

            $field_label = $detail['title'];

            for ($j = 0; $j <= 1; $j++) {
                $filters_form->add("id_" . ($i * 2 + $j), 'hidden', array(
                    'label' => $j == 0 ? $field_label : ' ',
                    'required' => false,
                    'empty_data' => $detail['id'],
                    'attr' => array('value' => $detail['id'])
                ))
                    ->add("operator_" . ($i * 2 + $j), 'choice', array(
                        'label' => false,
                        'required' => false,
                        'choices' => $this->getDynamicQueryUtilManager()->getConditionalOperatorsChoices($detail['type']),
                        'multiple' => false,
                        'expanded' => false
                    ));

                if (in_array($input_types_for_attr_types[$detail['type']], array('date', 'time', 'datetime'))) {
                    $filters_form->add("value_" . ($i * 2 + $j), $input_types_for_attr_types[$detail['type']], array(
                        'label' => false,
                        'required' => false,
                        'widget' => 'single_text',
                        'attr' => array('class' => $detail['type'], 'type' => $detail['type'])
                    ));
                } else {
                    $filters_form->add("value_" . ($i * 2 + $j), $input_types_for_attr_types[$detail['type']], array(
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

    // TODO: ver para donde pasar esto que es de la vista

    /**
     * @return array
     * @param DataModel $datamodel
     */
    public function getSubmittedDataFromFiltersForm(DataModel $datamodel, \Symfony\Component\HttpFoundation\Request $request)
    {
        $details_for_filter = $this->getDatamodelDetailManager()->descriptionForPublicDetailsFromQuery($datamodel->getId());

        $details_for_filter_by_ids = array();

        foreach ($details_for_filter as $dff) {
            $details_for_filter_by_ids[$dff['id']] = $dff;
        }

        $filters_form = $this->createFiltersFormForQuery($datamodel); // pasar settings y no el response

        $filters_form->handleRequest($request); // or handleRequest?

        if (!$filters_form->isValid()) { // if ($request->isXmlHttpRequest())
            //$this->throwException("ERROR!!!. Filter data isn´t valid");
        }

        $data = $filters_form->getData();

        return $data;
    }

    /**
     * @return array
     * @param DataModel $datamodel
     */
    public function getFiltersValuesFromFiltersFormData($datamodel, \Symfony\Component\HttpFoundation\Request $request)
    {

        $details_for_filter_by_ids = $this->getDatamodelDetailManager()->descriptionForPublicDetailsFromQuery($datamodel->getId());

        $data = $this->getSubmittedDataFromFiltersForm($datamodel, $request);

        $_filters_count = (int)$data['_filters_count'];

        $filter_by = array();

        for ($i = 0; $i < $_filters_count; $i++) {

            $detail_id = $data['id_' . $i];

            $operator = $data['operator_' . $i];

            $value = $data['value_' . $i];

            if (($operator != null && strpos($operator, 'UNARY') != false) || ($value != null && $value != '' && $operator != null && $operator != '')
            ) {
                if ($details_for_filter_by_ids[$detail_id]['type'] == 'date') {
                    $value = $value->format('Y-m-d');
                } else if ($details_for_filter_by_ids[$detail_id]['type'] == 'time') {
                    $value = $value->format('H:i:s');
                } else if ($details_for_filter_by_ids[$detail_id]['type'] == 'datetime') {
                    $value = $value->format('Y-m-d H:i:s');
                }
                $filter_by[] = array(
                    'detail_id' => $detail_id,
                    'operator' => $operator,
                    'value' => $value,
                );
            }
        }

        return $filter_by;
    }

    /**
     * @return array
     * @param DataModel $datamodel
     */
    public function getOrdersValuesFromFiltersFormData(DataModel $datamodel, \Symfony\Component\HttpFoundation\Request $request)
    {

        $order_by = array();

        $data = $this->getSubmittedDataFromFiltersForm($datamodel, $request);

        if (!empty($data['_sort_by']) && !empty($data['_sort_order'])) {
            $order_by[] = array(
                'detail_id' => $data['_sort_by'],
                'order_asc_desc' => $data['_sort_order']
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