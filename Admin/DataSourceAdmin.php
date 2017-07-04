<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use TechPromux\Bundle\BaseBundle\Admin\Resource\BaseResourceAdmin;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataSource;
use TechPromux\Bundle\DynamicQueryBundle\Manager\DataSourceManager;

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

     //------------------------------------------------------------------------------------

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            ->add('name', null, array(
                'global_search' => false
            ))
            ->add('driverType', null, array(
                'global_search' => false
            ))
            ->add('dbHost', null, array(
                'global_search' => false
            ))
            ->add('dbName', null, array(
                'global_search' => false
            ));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            //->add('description', 'html')
            ->add('dbHost', 'string', array(
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ))
            ->add('dbPort', 'string', array(
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ))
            ->add('driverType', 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicQueryManager()->getDriverTypesChoices(),
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ))
            ->add('dbName', 'string', array(
                'row_align' => 'left',
                'header_style' => 'width: 120px',
            ))
            /* ->add('metadatas', null, array(
              'label' => 'Metadatas',
              'row_align' => 'left',
              'header_style' => 'width: 30%',
              //'associated_property' => 'selectedTableNameOrCustomQuery',
              'route' => array('name' => '__'),
              )) */
            ->add('enabled', null, array('editable' => true,
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ));

        parent::configureListFields($listMapper);

        $listMapper->add('_action', 'actions', array(
            'label' => 'Actions',
            'row_align' => 'center',
            'header_style' => 'width: 120px',
            'actions' => array(
                //'show' => array(),
                'reload' => array(
                    'template' => 'TechPromuxBaseBundle:Admin:CRUD/list__action_reload.html.twig'
                ),
                'edit' => array(),
                //'tech_prommux_dynamic_query.admin.datasourceconnection|tech_prommux_dynamic_query.admin.datasourcemetadata.list' => array(
                //    'template' => 'TechPromuxDynamicQueryBundle:Metadata:list__action_datasourceconnection_metadata.html.twig'
                //),
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
            $plain_password = $this->getResourceManager()->decodeReversibleString($encoded_password);
            $object->setPlainPassword($plain_password);
        }

        $formMapper
            ->with('form.group.datasource.description', array("class" => "col-md-7"))
            ->add('name')
            ->add('description')
            //->add('code')
            ->add('enabled')
            ->end()
            ->with('form.group.datasource.parameters', array("class" => "col-md-5"))
            ->add('driverType', 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicQueryManager()->getDriverTypesChoices(),
                'required' => true,
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('dbHost', null, array(
                'empty_data' => '127.0.0.1',
            ))
            ->add('dbPort', null, array(
                'empty_data' => '3306',
            ))
            ->add('dbName')
            ->add('dbUser')
            ->add('plainPassword', 'password', array(
                'required' => false,
                'trim' => false,
                //'help' => 'Don\'t edit if you want mantain the same'
            ))
            ->end();
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('title')
            ->add('description')
            ->add('enabled')
            ->add('driverType')
            ->add('dbHost')
            ->add('dbPort')
            ->add('dbName')
            ->add('dbUser')
            //->add('dbPassword')
            //->add('metadataInfo')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('options')
            //->add('securityToken')
            //->add('id')
        ;
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
            ->with('description')
            ->assertNotBlank()
            ->assertLength(array('min' => 5))
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

    //--------------------------------------------------------------------------------------------

    protected function configureSideMenu2(\Knp\Menu\ItemInterface $menu, $action, \Sonata\AdminBundle\Admin\AdminInterface $childAdmin = null)
    {

        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        if ($admin->getRequest()->get('childId') || ($admin->getRequest()->get('id') && in_array($action, array('list')))) {
            $menu->addChild('link_action_connection_edit', array(
                    'uri' => $admin->generateUrl('edit', array('id' => $id))
                )
            );
        }
        if ($admin->getRequest()->get('id') && !$admin->getRequest()->get('childId') && in_array($action, array('edit'))) {
            $menu->addChild('link_action_datasourceconnection_metadata_list', array(
                    'uri' => $admin->generateUrl('tech_prommux_dynamic_query.admin.datasourceconnection|tech_prommux_dynamic_query.admin.datasourcemetadata.list', array('id' => $id))
                )
            );
        }

        if ($admin->getRequest()->get('id') && $admin->getRequest()->get('childId') && in_array($action, array('edit'))) {
            $menu->addChild('link_action_datasourceconnection_metadata_list', array(
                    'uri' => $this->getChild('tech_prommux_dynamic_query.admin.datasourcemetadata')->generateUrl('list', array())
                )
            );
        }
    }




}
