<?php

namespace TechPromux\DynamicQueryBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TechPromux\DynamicQueryBundle\Entity\DataSource;

class DataSourceAdminController extends CRUDController
{
    public function reloadAction($id) {

        $object = $this->admin->getSubject(); /* @var $object DataSource */

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->preUpdate($object);
        $this->admin->update($object);
        $this->admin->postUpdate($object);

        $this->addFlash('sonata_flash_success', $this->trans('DataSource Information reloaded successfully'));

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
