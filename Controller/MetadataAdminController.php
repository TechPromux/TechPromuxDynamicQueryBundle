<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MetadataAdminController extends CRUDController {

    public function copyAction($id) {

        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $newMetadata = $this->admin->getResourceManager()->createCopyForMetadata($object);

        $this->addFlash('sonata_flash_success', 'Copy created successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

}
