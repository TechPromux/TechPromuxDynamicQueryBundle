<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TechPromux\Bundle\DynamicQueryBundle\Manager\DataModelManager;

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

        $this->admin->checkAccess('duplicate', $object);

        $duplicatedQuery = $this->admin->getManager()->duplicateQuery($object);

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

        $filter_by = $manager->getFiltersValuesFromFiltersFormData($object, $request);

        $order_by = $manager->getOrdersValuesFromFiltersFormData($object, $request);

        $datamodel = $object;

        $queryBuilder = $manager->getQueryBuilderFromDataModel($datamodel);

        $queryBuilder = $manager->appendFilters($queryBuilder, $filter_by, $order_by);

        $paginator = $manager->paginatorFromQueryBuilder($queryBuilder, $request);

        $currentPageResults = $paginator->getCurrentPageResults();

        dump($queryBuilder);

        //throw 1;

        dump($queryBuilder->getSQL());

        //throw 2;

        dump($currentPageResults);

        //throw 3;

        $filter_form = $manager->createFiltersFormForQuery($object);

        return $this->render($request->isXmlHttpRequest() ? "TechPromuxDynamicQueryBundle:Admin:DataModel/execute.table.html.twig" : "TechPromuxDynamicQueryBundle:Admin:DataModel/execute.html.twig", array(
            'action' => 'execute',
            'object' => $object,
            'paginator' => $paginator,
            'result' => $currentPageResults,
            'public_fields_descriptions' => $manager->getDatamodelDetailManager()->descriptionForPublicDetailsFromQuery($object->getId()),
            'filters_form' => $filter_form->createView(),
            'details_for_filter' => $manager->getDatamodelDetailManager()->descriptionForPublicDetailsFromQuery($object),
            'formatter_helper' => $manager->getDynamicQueryUtilManager(),
            'locale' => $manager->localeFromAuthenticatedUser()
        ), null, $request);
    }

    //------------------------------------------------------------------------------
}
