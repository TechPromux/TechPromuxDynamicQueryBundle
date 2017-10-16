<?php

namespace TechPromux\DynamicQueryBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use TechPromux\BaseBundle\Admin\Resource\BaseResourceAdmin;
use TechPromux\DynamicQueryBundle\Entity\DataSource;
use TechPromux\DynamicQueryBundle\Manager\DataSourceManager;

class DataSourceAdmin extends BaseResourceAdmin
{

    protected $accessMapping = array(
        'reload' => 'EDIT',
    );

    /**
     * @return DataSourceManager
     */
    public function getResourceManager()
    {
        return parent::getResourceManager();
    }

    /**
     * @return DataSource
     */
    public function getSubject()
    {
        return parent::getSubject();
    }

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('show');
        $collection->add('reload', $this->getRouterIdParameter() . '/reload');
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        if (!$this->isGranted('EDIT')) {
            $query->andWhere($query->getRootAliases()[0] . '.enabled = 1');
        }
        return $query;
    }

    public function toString($object)
    {
        return $object->getName() ?: '';
    }

    //------------------------------------------------------------------------------------

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            ->add('name')
            ->add('title')
            ->add('driverType', null, [], 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicQueryManager()->getDriverTypesChoices(),
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('dbHost')
            ->add('dbPort')
            ->add('dbName')
            ->add('enabled');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('title')
            //->add('description', 'html')
            ->add('driverType', 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicQueryManager()->getDriverTypesChoices(true),
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ))
            ->add('dbHost', 'string', array(
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ))
            ->add('dbPort', 'string', array(
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ))
            ->add('dbName', 'string', array(
                'row_align' => 'left',
                'header_style' => 'width: 120px',
            ))
            ->add('enabled', null, array('editable' => true,
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ));

        parent::configureListFields($listMapper);

        $listMapper->add('_action', 'actions', array(
            //'label' => 'Actions',
            'row_align' => 'center',
            'header_style' => 'width: 120px',
            'actions' => array(
                //'show' => array(),
                'edit' => array(),
                'reload' => array(
                    'template' => 'TechPromuxBaseBundle:Admin:CRUD/list__action_reload.html.twig'
                ),
                'delete' => array(),
            )
        ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $object = $this->getSubject();

        if (!is_null($object) && !is_null($object->getId())) {
            $encoded_password = $object->getDbPassword();
            $plain_password = $this->getResourceManager()->getUtilDynamicQueryManager()->getSecurityManager()->decodeReversibleString($encoded_password);
            $object->setPlainPassword($plain_password);
        }

        $formMapper
            ->with('form.group.datasource.description', array("class" => "col-md-5"));

        $formMapper->add('name');

        $formMapper
            ->add('title', null, [
                'required'=>true
            ]);
        $formMapper
            //->add('description')
            //->add('code')
            ->add('enabled');
        $formMapper
            ->end();
        $formMapper
            ->with('form.group.datasource.parameters', array("class" => "col-md-7"));
        $formMapper
            ->add('driverType', 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicQueryManager()->getDriverTypesChoices(),
                'required' => true,
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ));
        $formMapper
            ->add('dbHost', null, array(
                'empty_data' => '127.0.0.1',
            ));
        $formMapper
            ->add('dbPort', null, array(
                'empty_data' => '3306',
            ));
        $formMapper
            ->add('dbName');
        $formMapper
            ->add('dbUser');
        $formMapper
            ->add('plainPassword', 'password', array(
                'required' => false,
                'trim' => false,
                //'help' => 'Don\'t edit if you want mantain the same'
            ));
        $formMapper
            ->end();
    }

    //----------------------------------------------------------------------------

    public function getNewInstance()
    {
        $object = parent::getNewInstance(); // TODO: Change the autogenerated stub
        $object->setEnabled(true);
        return $object;
    }

    /**
     * @param ErrorElement $errorElement
     * @param DataSource $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {

        parent::validate($errorElement, $object);

        $errorElement
            ->with('name')
            ->assertNotBlank()
            ->assertLength(array('min' => 3))
            ->end()
            ->with('title')
            ->assertNotBlank()
            ->assertLength(array('min' => 3))
            ->end()
            ->with('driverType')
            ->assertNotNull()
            ->end()
            ->with('dbHost')
            ->assertNotBlank()
            ->assertIp(array('version' => 'all'))
            ->end()
            ->with('dbName')
            ->assertNotBlank()
            ->end()
            ->with('dbUser')
            ->assertNotBlank()
            ->end();

        if (!$this->getResourceManager()->validateConnectionSettings(
            $object->getDriverType(),
            $object->getDbHost(),
            $object->getDbPort(),
            $object->getDbName(),
            $object->getDbUser(),
            $object->getPlainPassword()
        )
        ) {
            $errorElement->addViolation($this->trans('Fail to check data for real connection'));
        }

    }

    /**
     * @param DataSource $object
     */
    public function preUpdate($object)
    {
        if (empty($object->getPlainPassword()) && !$this->getRequest()->isXmlHttpRequest()) {
            $object->setPlainPassword('');
        }
        parent::preUpdate($object); // TODO: Change the autogenerated stub
    }
}
