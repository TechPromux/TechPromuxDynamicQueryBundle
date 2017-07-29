<?php

namespace  TechPromux\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use  TechPromux\BaseBundle\Entity\Resource\BaseResource;

/**
 * DataModelOrder
 *
 * @ORM\Table(name="techpromux_dynamic_query_datamodel_order")
 * @ORM\Entity()
 */
class DataModelOrder extends BaseResource
{
    /**
     * @ORM\ManyToOne(targetEntity="DataModel", inversedBy="orders")
     * @ORM\JoinColumn(name="datamodel_id", referencedColumnName="id", nullable=false)
     */
    private $datamodel;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataField", inversedBy="orders")
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
     * @ORM\Column(name="sql_name", type="string", length=255, nullable=true)
     */
    private $sqlName;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    //-----------------------------------------------------------------------------------


    public function __toString()
    {
        return $this->getName()?:'';
    }

    //------------------------------------------------------------------------------------



    /**
     * Set function
     *
     * @param string $function
     *
     * @return DataModelOrder
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
     * Set sqlName
     *
     * @param string $sqlName
     *
     * @return DataModelOrder
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
     * Set type
     *
     * @param string $type
     *
     * @return DataModelOrder
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return DataModelOrder
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
     * @param \TechPromux\DynamicQueryBundle\Entity\DataModel $datamodel
     *
     * @return DataModelOrder
     */
    public function setDatamodel(\TechPromux\DynamicQueryBundle\Entity\DataModel $datamodel)
    {
        $this->datamodel = $datamodel;

        return $this;
    }

    /**
     * Get datamodel
     *
     * @return \TechPromux\DynamicQueryBundle\Entity\DataModel
     */
    public function getDatamodel()
    {
        return $this->datamodel;
    }

    /**
     * Set field
     *
     * @param \TechPromux\DynamicQueryBundle\Entity\MetadataField $field
     *
     * @return DataModelOrder
     */
    public function setField(\TechPromux\DynamicQueryBundle\Entity\MetadataField $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \TechPromux\DynamicQueryBundle\Entity\MetadataField
     */
    public function getField()
    {
        return $this->field;
    }
}
