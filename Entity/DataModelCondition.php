<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;

/**
 * DataModelCondition
 *
 * @ORM\Table(name="techpromux_dynamic_query_datamodel_condition")
 * @ORM\Entity()
 */
class DataModelCondition extends BaseResource
{
    /**
     * @ORM\ManyToOne(targetEntity="DataModel", inversedBy="conditions")
     * @ORM\JoinColumn(name="datamodel_id", referencedColumnName="id", nullable=false)
     */
    private $datamodel;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataField", inversedBy="conditions")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id", nullable=false)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="function", type="string", length=255, nullable=true)
     */
    private $function;

    /**
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=255)
     */
    private $operator;

    /**
     * @var string
     *
     * @ORM\Column(name="compare_to_type", type="string", length=255, nullable=true)
     */
    private $compareToType;

    /**
     * @var string
     *
     * @ORM\Column(name="compare_to_fixed_value", type="text", nullable=true)
     */
    private $compareToFixedValue;

    /**
     * @var string
     *
     * @ORM\Column(name="compare_to_dynamic_value", type="string", length=255, nullable=true)
     */
    private $compareToDynamicValue;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataField")
     * @ORM\JoinColumn(name="compare_to_field_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $compareToField;

    /**
     * @var string
     *
     * @ORM\Column(name="compare_to_field_function", type="string", length=255, nullable=true)
     */
    private $compareToFunction;

    /**
     * @var string
     *
     * @ORM\Column(name="sql_name", type="string", length=255, nullable=true)
     */
    private $sqlName;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;


    //-------------------------------------------------------------------------


    public function __toString()
    {
        return $this->getName() ?: '';
    }

    //---------------------------------------------------------------------------

    /**
     * Set function
     *
     * @param string $function
     *
     * @return DataModelCondition
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set operator
     *
     * @param string $operator
     *
     * @return DataModelCondition
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set compareToType
     *
     * @param string $compareToType
     *
     * @return DataModelCondition
     */
    public function setCompareToType($compareToType)
    {
        $this->compareToType = $compareToType;

        return $this;
    }

    /**
     * Get compareToType
     *
     * @return string
     */
    public function getCompareToType()
    {
        return $this->compareToType;
    }

    /**
     * Set compareToFixedValue
     *
     * @param string $compareToFixedValue
     *
     * @return DataModelCondition
     */
    public function setCompareToFixedValue($compareToFixedValue)
    {
        $this->compareToFixedValue = $compareToFixedValue;

        return $this;
    }

    /**
     * Get compareToFixedValue
     *
     * @return string
     */
    public function getCompareToFixedValue()
    {
        return $this->compareToFixedValue;
    }

    /**
     * Set compareToDynamicValue
     *
     * @param string $compareToDynamicValue
     *
     * @return DataModelCondition
     */
    public function setCompareToDynamicValue($compareToDynamicValue)
    {
        $this->compareToDynamicValue = $compareToDynamicValue;

        return $this;
    }

    /**
     * Get compareToDynamicValue
     *
     * @return string
     */
    public function getCompareToDynamicValue()
    {
        return $this->compareToDynamicValue;
    }

    /**
     * Set compareToFunction
     *
     * @param string $compareToFunction
     *
     * @return DataModelCondition
     */
    public function setCompareToFunction($compareToFunction)
    {
        $this->compareToFunction = $compareToFunction;

        return $this;
    }

    /**
     * Get compareToFunction
     *
     * @return string
     */
    public function getCompareToFunction()
    {
        return $this->compareToFunction;
    }

    /**
     * Set sqlName
     *
     * @param string $sqlName
     *
     * @return DataModelCondition
     */
    public function setSqlName($sqlName)
    {
        $this->sqlName = $sqlName;

        return $this;
    }

    /**
     * Get sqlName
     *
     * @return string
     */
    public function getSqlName()
    {
        return $this->sqlName;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return DataModelCondition
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set datamodel
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel $datamodel
     *
     * @return DataModelCondition
     */
    public function setDatamodel(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel $datamodel)
    {
        $this->datamodel = $datamodel;

        return $this;
    }

    /**
     * Get datamodel
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel
     */
    public function getDatamodel()
    {
        return $this->datamodel;
    }

    /**
     * Set field
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $field
     *
     * @return DataModelCondition
     */
    public function setField(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set compareToField
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $compareToField
     *
     * @return DataModelCondition
     */
    public function setCompareToField(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $compareToField = null)
    {
        $this->compareToField = $compareToField;

        return $this;
    }

    /**
     * Get compareToField
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField
     */
    public function getCompareToField()
    {
        return $this->compareToField;
    }
}
