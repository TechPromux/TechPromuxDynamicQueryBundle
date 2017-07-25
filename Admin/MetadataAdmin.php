<?php

namespace  TechPromux\DynamicQueryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use  TechPromux\BaseBundle\Admin\Resource\BaseResourceAdmin;
use  TechPromux\DynamicQueryBundle\Entity\Metadata;
use  TechPromux\DynamicQueryBundle\Manager\MetadataManager;

class MetadataAdmin extends BaseResourceAdmin
{
    //protected $parentAssociationMapping = 'datasource';

    /**
     * @return MetadataManager
     */
    public function getResourceManager()
    {
        return parent::getResourceManager();
    }

    //----------------------------------------------------------------------------

    protected $accessMapping = array(
        'copy' => 'COPY',
    );

    //----------------------------------------------------------------------------

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        //if (!$this->getParent()) {
        //    $collection->clear();
        //} else {
        //    $collection->clearExcept(array('list', 'create', 'edit', 'delete'));
        //    $collection->add('duplicate', $this->getRouterIdParameter() . '/duplicate');
        //}
        $collection->add('copy', $this->getRouterIdParameter() . '/copy');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            //->add('datasource')
            //->add('title')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('title')
            //->add('datasource', 'string')
            ->add('tables', null, array(
                'row_align' => 'left',
                'header_style' => 'width: 30%',
                //'associated_property' => 'selectedTableNameOrCustomQuery',
                'route' => array('name' => '__'),
            ))
            ->add('enabled', null, array('editable' => true,
                'row_align' => 'center',
                'header_style' => 'width: 100px',
            ))//->add('description', 'html')
        ;

        parent::configureListFields($listMapper);
        $listMapper
            ->add('_action', 'actions', array(
                'label' => 'Actions',
                'row_align' => 'right',
                'header_style' => 'width: 120px',
                //'header_class' => 'fa fa-table',
                'actions' => array(
                    //'show' => array(),
                    'edit' => array(),
                    'copy' => array(
                        'template' => 'TechPromuxBaseBundle:Admin:CRUD/list__action_copy.html.twig'
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

        $parent = false;

        if ($this->getParent() && $object->getId() === null) {
            $parent = $this->getParent()->getSubject();
            $object->setdatasource($parent);
        }

        $datasourceManager = $this->getResourceManager()->getDataSourceManager();

        $formMapper
            ->tab('form.tab.datasource');


        $formMapper
            ->with('form.group.datasource.descriptions', array("class" => "col-md-8"))
            ->add('name')
            ->add('title')
            ->add('description')
            ->end();

        $formMapper
            ->with('form.group.datasource.selectdatasource', array("class" => "col-md-4"))
            ->add('datasource', 'entity',
                array(
                    'class' => $datasourceManager->getResourceClass(),
                    'disabled' => !is_null($object->getId()),
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($datasourceManager) {
                        $qb = $er->createQueryBuilder('t');
                        $qb = $datasourceManager->alterBaseQueryBuilder($qb);
                        return $qb;
                    },
                    'choice_label' => 'name',
                    "multiple" => false, "expanded" => false, 'required' => true)
            )
            ->add('enabled')
            ->end();

        $formMapper
            ->end();

        if ($object->getId() != null) {
            $formMapper
                ->tab('form.tab.tables')
                ->with('form.group.tables.selecttablesandqueries')
                ->add('tables', 'sonata_type_collection', array(
                    "label" => false,
                    "by_reference" => false,
                    'type_options' => array(
                        'delete' => true
                    )
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    //'sortable' => 'position',
                ))
                ->end()
                ->end();
        }
        if ($object->getId() != null && !$object->getTables()->isEmpty()) { // y las tablas han sido seleccionadas???
            $formMapper
                ->tab('form.tab.relations')
                ->with('form.group.relations.selecttablesandrelations')
                ->add('relations', 'sonata_type_collection', array(
                    "label" => false,
                    "by_reference" => false,
                    //'cascade_validation' => false,
                    'type_options' => array('delete' => true)
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    //'sortable' => 'position',
                ))
                ->end()
                ->end();
        }
        if ($object->getId() != null && !$object->getTables()->isEmpty()) {
            $formMapper
                ->tab('form.tab.fields')
                ->with('form.group.fields.selectfields')
                ->add('fields', 'sonata_type_collection', array(
                    "label" => false,
                    "by_reference" => false,
                    'type_options' => array(
                        'delete' => false,

                    ),
                    'btn_add' => false,
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    //'sortable' => 'position',
                ))
                ->end()
                ->end();
        }
    }

    //--------------------------------------------------------------------------------------

    public function getNewInstance()
    {
        $object = parent::getNewInstance(); // TODO: Change the autogenerated stub
        $object->setEnabled(true);
        return $object;
    }

    /**
     * @param \Sonata\CoreBundle\Validator\ErrorElement $errorElement
     * @param Metadata $object
     */
    public function validate(\Sonata\CoreBundle\Validator\ErrorElement $errorElement, $object)
    {

        parent::validate($errorElement, $object);

        $errorElement
            ->with('datasource')
            ->assertNotBlank()
            ->end()
            ->with('title')
            ->assertNotBlank()
            ->assertLength(array('min' => 3))
            ->end()
            ->with('description')
            ->assertNotBlank()
            ->assertLength(array('min' => 5))
            ->end();

        if ($object->getId()) {

            $errorElement
                ->with('tables')
                ->assertCount(array('min' => 1, 'minMessage' => 'You must indicate at least a TABLE or a CUSTOM QUERY'))
                ->end();

            if (count($object->getTables()) > 0) {
                $one_persisted = false;
                foreach ($object->getTables() as $t) {
                    if (!is_null($t->getId()))
                        $one_persisted = true;
                }
                if ($one_persisted) {
                    $one_selected = false;
                    foreach ($object->getFields() as $f) {
                        if ($f->getEnabled())
                            $one_selected = true;
                    }

                    if (!$one_selected) {
                           $errorElement->with('fields')->addViolation('You must select at least a FIELD');
                    }
                }
            }
        }
    }
}
