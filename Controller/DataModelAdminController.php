<?php

namespace  TechPromux\DynamicQueryBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use  TechPromux\DynamicQueryBundle\Manager\DataModelManager;

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

        $filter_form = $manager->createFilterFormFromDataModel($object);

        $filter_form->handleRequest($request); // or handleRequest?

        $filter_data = $filter_form->getData();

        //dump($filter_data);

        $filter_by = $manager->getFiltersValuesFromFiltersFormData($object, $filter_data);

        $order_by = $manager->getOrdersValuesFromFiltersFormData($object, $filter_data);

        //
        $queryBuilder = $manager->appendFilters($queryBuilder, $filter_by, $order_by);

        //dump($queryBuilder);
        //dump($queryBuilder->getSQL());


        // get results

        $paginator = $manager->paginatorFromQueryBuilder($queryBuilder, $request);

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
            'data' => !is_null($filter_data) ? $filter_data : array('_sort_by' => '', '_sort_order' => '')
        );

        $helpers = array(
            'formatter' => $manager->getUtilDynamicQueryManager(),
            'locale' => $manager->getLocaleFromAuthenticatedUser(),
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
}
