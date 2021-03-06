<?php

namespace TechPromux\DynamicQueryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use TechPromux\BaseBundle\Admin\Resource\BaseResourceAdmin;
use TechPromux\DynamicQueryBundle\Manager\MetadataManager;
use TechPromux\DynamicQueryBundle\Manager\MetadataRelationManager;

class MetadataRelationAdmin extends BaseResourceAdmin
{

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection->clearExcept(array('create', 'edit', 'delete'));
    }

    /**
     * @return MetadataRelationManager
     */
    public function getResourceManager()
    {
        return parent::getResourceManager(); // TODO: Change the autogenerated stub
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        parent::configureFormFields($formMapper);

        $object = $this->getSubject();

        $metadata = $this->getParentFieldDescription()->getAdmin()->getSubject();

        $tables_and_columns = $this->getResourceManager()->getMetadataManager()->getTablesAndColumnsDescriptionsFromMetadata($metadata);

        $left_columns_names = array();
        $right_columns_names = array();

        foreach ($tables_and_columns as $table) {
            for ($i = 0; $i < count($table['columns']); $i++) {
                $left_columns_names[$table['columns'][$i]['name']] = $table['columns'][$i]['name'];
                $right_columns_names[$table['columns'][$i]['name']] = $table['columns'][$i]['name'];
            }
        }

        $formMapper
            ->add('position', 'hidden', array(
                'attr' => array('data-ctype' => 'metadata-relation-position',
                )
            ))
            ->add('leftTable', 'entity', array('class' => $this->getResourceManager()->getMetadataManager()->getMetadataTableManager()->getResourceClass(),
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($metadata) {
                        $qb = $er->createQueryBuilder('t');
                        $qb->leftJoin('t.metadata', 'm');
                        $qb->where($qb->expr()->eq('m.id', '\'' . $metadata->getId() . '\''));
                        $qb->addOrderBy('t.position', 'ASC');
                        return $qb;
                    },
                    'choice_label' => 'name',
                    'attr' => array('data-ctype' => 'metadata-relation-lefttable'),
                    "multiple" => false, "expanded" => false, 'required' => true)
            )
            ->add('leftColumn', 'choice', array(
                "choices" => $left_columns_names,
                "expanded" => false,
                "multiple" => false,
                'required' => true,
                'attr' => array(
                    'data-ctype' => 'metadata-relation-leftcolumn'),
            ))
            ->add('joinType', 'choice', array(
                "choices" => $this->getResourceManager()->getMetadataManager()->getUtilDynamicQueryManager()->getTableRelationTypesChoices(),
                "expanded" => false,
                "multiple" => false,
                'required' => true,
                'attr' => array('data-ctype' => 'metadata-relation-jointype'),
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('rightTable', 'entity', array('class' => $this->getResourceManager()->getMetadataManager()->getMetadataTableManager()->getResourceClass(),
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($metadata) {
                        $qb = $er->createQueryBuilder('t');
                        $qb->leftJoin('t.metadata', 'm');
                        $qb->where($qb->expr()->eq('m.id', '\'' . $metadata->getId() . '\''));
                        $qb->addOrderBy('t.position', 'ASC');
                        return $qb;
                    },
                    'choice_label' => 'name',
                    'attr' => array('data-ctype' => 'metadata-relation-righttable'),
                    "multiple" => false, "expanded" => false, 'required' => true)
            )
            ->add('rightColumn', 'choice', array(
                "choices" => $right_columns_names,
                "expanded" => false,
                "multiple" => false,
                'required' => true,
                //'validation_groups' => false,
                'attr' => array('data-ctype' => 'metadata-relation-rightcolumn')
            ));
    }

    public function getNewInstance()
    {
        $object = parent::getNewInstance(); // TODO: Change the autogenerated stub
        $object->setEnabled(true);
        return $object;
    }

}
