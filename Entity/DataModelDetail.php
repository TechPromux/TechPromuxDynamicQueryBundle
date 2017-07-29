<?php

namespace  TechPromux\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use  TechPromux\BaseBundle\Entity\Resource\BaseResource;

/**
 * DataModelDetail
 *
 * @ORM\Table(name="techpromux_dynamic_query_datamodel_detail")
 * @ORM\Entity()
 */
class DataModelDetail extends BaseResource
{
    /**
     * @ORM\ManyToOne(targetEntity="DataModel", inversedBy="details")
     * @ORM\JoinColumn(name="datamodel_id", referencedColumnName="id", nullable=false)
     */
    private $datamodel;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataField", inversedBy="details")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id", nullable=false)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=255, nullable=true)
     */
    private $abbreviation;

    /**
     * @var string
     *
     * @ORM\Column(name="function", type="string", length=255, nullable=true)
     */
    private $function;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation_format", type="string", length=255, nullable=true)
     */
    private $presentationFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation_prefix", type="string", length=255, nullable=true)
     */
    private $presentationPrefix;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation_suffix", type="string", length=255, nullable=true)
     */
    private $presentationSuffix;

    /**
     * @var string
     *
     * @ORM\Column(name="sql_name", type="string", length=255, nullable=true)
     */
    private $sqlName;

    /**
     * @var string
     *
     * @ORM\Column(name="sql_type", type="string", length=255, nullable=true)
     */
    private $sqlType;

    /**
     * @var string
     *
     * @ORM\Column(name="sql_type_categorization", type="string", length=255, nullable=true)
     */
    private $sqlTypeCategorization;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;


    //-----------------------------------------------------------------------


    public function __toString()
    {
        return $this->getName()?:'';
    }

    /**
     * @return string
     */
    public function getSQLAlias()
    {
        return $this->getId() != null ? ('D' . str_replace('-', '_', $this->getId())) : '';
    }

    //---------------------------------------------------------------------------


    /**
     * Set abbreviation
     *
     * @param string $abbreviation
     *
     * @return DataModelDetail
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set function
     *
     * @param string $function
     *
     * @return DataModelDetail
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
     * Set presentationFormat
     *
     * @param string $presentationFormat
     *
     * @return DataModelDetail
     */
    public function setPresentationFormat($presentationFormat)
    {
        $this->presentationFormat = $presentationFormat;

        return $this;
    }

    /**
     * Get presentationFormat
     *
     * @return string
     */
    public function getPresentationFormat()
    {
        return $this->presentationFormat;
    }

    /**
     * Set presentationPrefix
     *
     * @param string $presentationPrefix
     *
     * @return DataModelDetail
     */
    public function setPresentationPrefix($presentationPrefix)
    {
        $this->presentationPrefix = $presentationPrefix;

        return $this;
    }

    /**
     * Get presentationPrefix
     *
     * @return string
     */
    public function getPresentationPrefix()
    {
        return $this->presentationPrefix;
    }

    /**
     * Set presentationSuffix
     *
     * @param string $presentationSuffix
     *
     * @return DataModelDetail
     */
    public function setPresentationSuffix($presentationSuffix)
    {
        $this->presentationSuffix = $presentationSuffix;

        return $this;
    }

    /**
     * Get presentationSuffix
     *
     * @return string
     */
    public function getPresentationSuffix()
    {
        return $this->presentationSuffix;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return DataModelDetail
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
     * @return DataModelDetail
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
     * @return DataModelDetail
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

    /**
     * @return string
     */
    public function getSqlName()
    {
        return $this->sqlName;
    }

    /**
     * @param string $sqlName
     * @return DataModelDetail
     */
    public function setSqlName($sqlName)
    {
        $this->sqlName = $sqlName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSqlType()
    {
        return $this->sqlType;
    }

    /**
     * @param string $sqlType
     * @return DataModelDetail
     */
    public function setSqlType($sqlType)
    {
        $this->sqlType = $sqlType;
        return $this;
    }

    /**
     * @return string
     */
    public function getSqlTypeCategorization()
    {
        return $this->sqlTypeCategorization;
    }

    /**
     * @param string $sqlTypeCategorization
     * @return DataModelDetail
     */
    public function setSqlTypeCategorization($sqlTypeCategorization)
    {
        $this->sqlTypeCategorization = $sqlTypeCategorization;
        return $this;
    }


}
