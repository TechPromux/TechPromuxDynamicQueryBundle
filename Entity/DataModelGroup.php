<?php

namespace TechPromux\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\BaseBundle\Entity\Resource\BaseResource;

/**
 * DataModelGroup
 *
 * @ORM\Table(name="techpromux_dynamic_query_datamodel_group")
 * @ORM\Entity()
 */
class DataModelGroup extends BaseResource
{
    /**
     * @ORM\ManyToOne(targetEntity="DataModel", inversedBy="groups")
     * @ORM\JoinColumn(name="datamodel_id", referencedColumnName="id", nullable=false)
     */
    private $datamodel;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataField", inversedBy="groups")
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
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;


    //-----------------------------------------------------------------------

    public function __toString()
    {
        return $this->getName()?$this->getName():'';
    }

    //---------------------------------------------------------------------------



    /**
     * Set function
     *
     * @param string $function
     *
     * @return DataModelGroup
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
     * @return DataModelGroup
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
     * @return DataModelGroup
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
     * @return DataModelGroup
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
     * @return DataModelGroup
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
