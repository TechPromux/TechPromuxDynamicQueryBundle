<?php

namespace TechPromux\DynamicQueryBundle\Controller;

use Pagerfanta\Pagerfanta;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use TechPromux\BaseBundle\Adapter\Paginator\DoctrineDbalPaginatorAdapter;
use TechPromux\DynamicQueryBundle\Entity\DataModel;
use TechPromux\DynamicQueryBundle\Manager\DataModelManager;

class DataModelAdminController extends CRUDController
{
    public function copyAction($id = null)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('copy', $object);

        $duplicatedDataModel = $this->admin->getResourceManager()->createCopyForDataModel($object);

        $this->addFlash('sonata_flash_success', 'Duplicated successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function executeAction($id)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('execute', $object);

        // PROCCESS QUERY BUILDER

        $manager = $this->admin->getResourceManager();
        /* @var $manager DataModelManager */

        $datamodel = $object;

        $queryBuilder = $manager->getQueryBuilderFromDataModel($datamodel);

        //dump($queryBuilder);

        // apply filters and orders options from request

        $filter_form = $this->createFiltersForm($request, $object, array());

        $filter_form->handleRequest($request); // or handleRequest?

        $filter_data = $filter_form->getData();

        //dump($filter_data);

        $filter_by = $this->createFiltersByDescriptions($request, $filter_data, $object, array());

        $order_by = $this->createOrdersByDescriptions($request, $filter_data, $object, array());

        //
        $queryBuilder = $manager->appendFilters($queryBuilder, $filter_by, $order_by);

        //dump($queryBuilder);
        //dump($queryBuilder->getSQL());


        // get results

        $paginator = $this->createPaginatorForQueryBuilder($queryBuilder);

        $paginator->setCurrentPage($request->get('_page', 1));
        $paginator->setMaxPerPage($request->get('_items_per_page', 32));

        $currentPageResults = $paginator->getCurrentPageResults();

        // preparing render data

        $details_descriptions = $manager->getEnabledDetailsDescriptionsFromDataModel($object->getId());

        $data = array(
            'details' => $details_descriptions,
            'paginator' => $paginator,
            'data' => $currentPageResults,
        );

        $filter = array(
            'details' => $details_descriptions,
            'form' => $filter_form->createView(),
            'data' => !is_null($filter_data) && !empty($filter_data) ? $filter_data : array('_sort_by' => '', '_sort_type' => '')
        );

        $helpers = array(
            'formatter' => $manager->getUtilDynamicQueryManager(),
            'locale' => $manager->getUtilDynamicQueryManager()->getSecurityManager()->getLocaleFromAuthenticatedUser(),
            'manager' => $manager,
        );

        //dump($data);
        //dump($filter);
        //dump($helpers);


        return $this->render("TechPromuxDynamicQueryBundle:Admin:DataModel/execute.html.twig", array(
            'action' => 'execute',
            'object' => $object,
            'data' => $data,
            'filter' => $filter,
            'helpers' => $helpers,

        ), null, $request);
    }

    //------------------------------------------------------------------------------

    /**
     * @param Request|null $request
     * @param DataModel $datamodel
     * @param array $parameters
     * @return mixed
     */
    protected function createFiltersForm(Request $request = null, DataModel $datamodel, array $parameters = array())
    {
        $filters_form_builder = $this->admin->getResourceManager()->createNamedFormBuilder(
            'filters_form_' . strtolower(str_replace('-', '_', $datamodel->getId())),
            array(),
            array(
                'csrf_protection' => false,
                'validation_groups' => array('filtering'),
            )
        );

        $filters_form_builder = $this->configureFiltersFormBuilder($request, $filters_form_builder, $datamodel, $parameters);

        return $filters_form_builder->getForm();

    }

    /**
     * @param Request|null $request
     * @param $filters_form_builder
     * @param DataModel $datamodel
     * @param array $parameters
     * @return mixed
     */
    protected function configureFiltersFormBuilder(Request $request = null, $filters_form_builder, DataModel $datamodel, array $parameters = array())
    {
        // TODO esto va en el otro abstract type (que lo llama desde le manager)?

        $datamodel_details_descriptions = $this->admin->getResourceManager()
            ->getEnabledDetailsDescriptionsFromDataModel($datamodel);

        $i = 0;
        foreach ($datamodel_details_descriptions as $detail) {

            $operators_for_detail_type = $this->admin->getResourceManager()
                ->getUtilDynamicQueryManager()
                ->getConditionalOperatorsChoices($detail['type']);

            $filters_form_builder->add("id_" . $i, 'hidden', array(
                'label' => $detail['title'],
                'required' => false,
                'empty_data' => $detail['id'],
                'attr' => array('value' => $detail['id'])
            ));
            $filters_form_builder->add("operator_" . $i, 'choice', array(
                'label' => false,
                'required' => false,
                'choices' => $operators_for_detail_type,
                'multiple' => false,
                'expanded' => false,
                'translation_domain' => $this->admin->getResourceManager()
                    ->getUtilDynamicQueryManager()->getBundleName()
            ));

            if (in_array($detail['type'], array('date', 'time', 'datetime'))) {
                $filters_form_builder->add("value_" . $i, $detail['type'], array(
                    'label' => false,
                    'required' => false,
                    'widget' => 'single_text',
                    'attr' => array('class' => $detail['type'], 'type' => $detail['type'])
                ));
            }
            if (in_array($detail['type'], array('boolean'))) {
                $filters_form_builder->add("value_" . $i, 'choice', array(
                    'label' => false,
                    'required' => false,
                    'choices' => array(
                        '0' => 'false',
                        '1' => 'true',
                    ),
                ));
            } else {
                $filters_form_builder->add("value_" . $i, 'text', array(
                    'label' => false,
                    'required' => false,
                ));
            }
            $i++;
        }

        $filters_form_builder->add("_filters_count", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => $i,
            'attr' => array('value' => $i)
        ));

        $filters_form_builder->add("_page", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => 1,
        ));
        $filters_form_builder->add("_items_per_page", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => 32,
        ));

        $filters_form_builder->add("_sort_by", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => '',
            'attr' => array('value' => '')
        ));
        $filters_form_builder->add("_sort_type", 'hidden', array(
            'label' => false,
            'required' => false,
            'empty_data' => '',
            'attr' => array('value' => '')
        ));

        return $filters_form_builder;
    }

    /**
     * @param Request $request
     * @param array $filter_form_data
     * @param DataModel $datamodel
     * @param array $parameters
     * @return array
     */
    protected function createFiltersByDescriptions(Request $request, array $filter_form_data, DataModel $datamodel, array $parameters)
    {
        $datamodel_details_descriptions = $this->admin->getResourceManager()
            ->getEnabledDetailsDescriptionsFromDataModel($datamodel);

        $operators = $this->admin->getResourceManager()
            ->getUtilDynamicQueryManager()->getRegisteredConditionalOperators();

        $filters_by = array();

        $i = 0;
        foreach ($datamodel_details_descriptions as $detail) {

            if (isset($filter_form_data['id_' . $i])) {

                $detail_id = $filter_form_data['id_' . $i];

                $operator_id = $filter_form_data['operator_' . $i];

                $value = $filter_form_data['value_' . $i];

                $detail_description = $datamodel_details_descriptions[$detail_id];

                if (isset($operators[$operator_id])) {
                    $operator = $operators[$operator_id];
                    /* @var $operator BaseConditionalOperatorType */

                    if ($operator->getIsUnary() || (!$operator->getIsUnary() && (!empty($value) || $value == 0 || $value == '0'))) {
                        if ($operator->getIsUnary()) {
                            $value = null;
                        } else {
                            if ($detail_description['type'] == 'date') {
                                $value = $value->format('Y-m-d') ? $value->format('Y-m-d') : $value->format('Y-m-d 00:00:00');
                            } else if ($detail_description['type'] == 'time') {
                                $value = $value->format('H:i:s') ? $value->format('H:i:s') : $value->format('Y-m-d H:i:s');
                            } else if ($detail_description['type'] == 'datetime') {
                                $value = $value->format('Y-m-d H:i:s');
                            }
                        }
                        $filters_by[] = array(
                            'id' => $detail_id,
                            'operator' => $operator->getId(),
                            'value' => $value,
                        );
                    }
                }
            }
            $i++;
        }

        return $filters_by;
    }

    /**
     * @param Request $request
     * @param array $filter_form_data
     * @param DataModel $datamodel
     * @param array $parameters
     * @return array
     */
    protected function createOrdersByDescriptions(Request $request, array $filter_form_data, DataModel $datamodel, array $parameters)
    {
        $orders_by = array();

        if (!empty($filter_form_data['_sort_by']) && !empty($filter_form_data['_sort_type'])) {
            $orders_by[] = array(
                'id' => $filter_form_data['_sort_by'],
                'type' => $filter_form_data['_sort_type']
            );
        }

        return $orders_by;
    }

    /**
     * @param $queryBuilder
     * @return DoctrineDbalPaginatorAdapter
     */
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
