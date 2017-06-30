<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use TechPromux\Bundle\BaseBundle\Admin\Resource\BaseResourceAdmin;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelCondition;
use TechPromux\Bundle\DynamicQueryBundle\Manager\DataModelConditionManager;

class DataModelConditionAdmin extends BaseResourceAdmin
{

    /**
     *
     * @return string
     */
    public function getResourceManagerID()
    {
        return 'techpromux_dynamic_query.manager.datamodel_condition';
    }

    /**
     * @return DataModelConditionManager
     */
    public function getResourceManager()
    {
        return parent::getResourceManager(); // TODO: Change the autogenerated stub
    }

    /**
     * @return DataModelCondition
     */
    public function getSubject()
    {
        return parent::getSubject(); // TODO: Change the autogenerated stub
    }

    //-----------------------------------------------------------------------------------------------


    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection->clearExcept(array('create', 'edit', 'delete'));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        parent::configureFormFields($formMapper);

        $object = $this->getSubject();

        $datamodel = $this->getParentFieldDescription()->getAdmin()->getSubject();
        /* @var $datamodel DataModel */

        $datamodel_manager = $this->getResourceManager()->getDatamodelManager();

        $metadata_field_manager = $datamodel_manager->getMetadataManager()->getMetadataFieldManager();

        $util_manager = $datamodel_manager->getDynamicQueryUtilManager();

        $metadata = $datamodel->getMetadata();

        // pedir el operator, buscarlo en la lista y preguntar si es unary, agregar dicha funcion a la interfaz de los operadores

        /*
        if ($object != null && ($object->getOperator() == null || explode('_', $object->getOperator())[1] == 'UNARY')) {
            $object->setCompareToType(null);
            $object->setCompareToFixedValue(null);
            $object->setCompareToDynamicValue(null);
            $object->setCompareToField(null);
            $object->setCompareToFunction(null);
        }
        */

        $formMapper
            ->add('position', 'hidden', array(
                'attr' => array('data-ctype' => 'datamodel-condition-position',
                )
            ))
            ->add('enabled', null, array(
                'required' => false,
                'attr' => array(
                    'data-ctype' => 'datamodel-condition-enabled'
                )
            ))
            ->add('field', 'entity', array(
                    'class' => $metadata_field_manager->getResourceClassShortcut(),
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($metadata_field_manager, $metadata) {
                        $qb = $metadata_field_manager->createQueryBuilderForMetadataAndEnabledsSelection($metadata);
                        return $qb;
                    },
                    'choice_label' => 'title',
                    'group_by' => 'table.title',
                    'attr' => array(
                        // 'class' => 'col-md-12',
                        'data-ctype' => 'datamodel-condition-field'
                    ),
                    "multiple" => false, "expanded" => false, 'required' => true)
            )
            ->add('function', 'choice', array(
                'choices' => $util_manager->getFieldFunctionsChoices(),
                'required' => false,
                'attr' => array(
                    //'class' => 'col-md-12',
                    'placeholder' => '------------ Select a function ------------',
                    'data-ctype' => 'datamodel-condition-function'
                ),
                "multiple" => false, "expanded" => false, 'required' => false,
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('operator', 'choice', array(
                'choices' => $util_manager->getConditionalOperatorsChoices(),
                'required' => true,
                'attr' => array(
                    //'class' => 'col-md-12',
                    'data-ctype' => 'datamodel-condition-operator'
                ),
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('compareToType', 'choice', array(
                'choices' => $util_manager->getConditionalCompareToTypeChoices(),
                'attr' => array(
                    //'class' => 'col-md-12',
                    'data-ctype' => 'datamodel-condition-comparetotype'),
                'required' => false, 'expanded' => false, 'multiple' => false,
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('compareToFixedValue', null, array(
                'required' => false,
                'disabled' => false, //($object != null && ($object->getValueType() != 'fixed' || $object->getOperator() == null || split('_', $object->getOperator())[1] == 'UNARY')), // preguntarlo de otra manera
                'attr' => array(
                    //'class' => 'col-md-12',
                    'data-ctype' => 'datamodel-condition-compareto-comparetofixed'
                ),
            ))
            ->add('compareToFunction', 'choice', array(
                'choices' => $util_manager->getFieldFunctionsChoices(),
                'attr' => array(
                    // 'class' => 'col-md-12',
                    'placeholder' => '------------ Select a function ------------',
                    'data-ctype' => 'datamodel-condition-comparetofunction'
                ),
                "multiple" => false, "expanded" => false, 'required' => false,
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('compareToDynamicValue', 'choice', array(
                'choices' => $util_manager->getComparablesDynamicValuesChoices(),
                'required' => false,
                'multiple' => false,
                'disabled' => false, //($object != null && ($object->getValueType() != 'dynamic' || $object->getOperator() == null || split('_', $object->getOperator())[1] == 'UNARY')),
                'attr' => array(
                    //'class' => 'col-md-12',
                    'placeholder' => '------------ Select a value ------------',
                    'data-ctype' => 'datamodel-condition-comparetodynamic'
                ),
                'translation_domain' => $this->getResourceManager()->getBundleName()
            ))
            ->add('compareToField', 'entity', array(
                    'class' => $metadata_field_manager->getResourceClassShortcut(),
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($metadata_field_manager, $metadata) {
                        $qb = $metadata_field_manager->createQueryBuilderForMetadataAndEnabledsSelection($metadata);
                        return $qb;
                    },
                    'choice_label' => 'title',
                    'group_by' => 'table.title',
                    'attr' => array(
                        //'class' => 'col-md-12',
                        'data-ctype' => 'datamodel-condition-comparetofield'
                    ),
                    "multiple" => false, "expanded" => false, 'required' => false,

                )
            );
    }

    public function validate(ErrorElement $errorElement, $object)
    {

        parent::validate($errorElement, $object);

        $errorElement
            ->with('field')
            ->assertNotBlank()
            ->end()
            ->with('operator')
            ->assertNotBlank()
            ->end();
        /*
            if ($object->getOperator() && split('_', $object->getOperator())[1] == 'UNARY') {
                $errorElement
                    ->with('valueType')
                    ->assertNull()
                    ->end()
                    ->with('fixedValueToCompare')
                    ->assertNull()
                    ->end()
                    ->with('dynamicValueToCompare')
                    ->assertNull()
                    ->end();
            } else if ($object->getOperator() && split('_', $object->getOperator())[1] == 'BINARY') {
                $errorElement->with('valueType')
                    ->assertNotBlank()
                    ->end();
                if ($object->getValueType() == 'fixed') {
                    $errorElement
                        ->with('fixedValueToCompare')
                        ->assertNotBlank()
                        ->end()
                        ->with('dynamicValueToCompare')
                        ->assertNull()
                        ->end();
                } elseif ($object->getValueType() == 'dynamic') {
                    $errorElement
                        ->with('fixedValueToCompare')
                        ->assertNull()
                        ->end()
                        ->with('dynamicValueToCompare')
                        ->assertNotBlank()
                        ->end();
                }
            }*/
    }

}
