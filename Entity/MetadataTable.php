<?php

namespace  TechPromux\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use  TechPromux\BaseBundle\Entity\Resource\BaseResource;

/**
 * TableMetatada
 *
 * @ORM\Table(name="techpromux_dynamic_query_metadata_table")
 * @ORM\Entity()
 */
class MetadataTable extends BaseResource
{
    /**
     * @ORM\ManyToOne(targetEntity="Metadata", inversedBy="tables")
     * @ORM\JoinColumn(name="metadata_id", referencedColumnName="id", nullable=false)
     */
    private $metadata;

    /**
     * @ORM\OneToMany(targetEntity="MetadataField", mappedBy="table", cascade={"all"}, orphanRemoval=true)
     */
    private $fields;

    /**
     * @ORM\OneToMany(targetEntity="MetadataRelation", mappedBy="leftTable", cascade={"all"}, orphanRemoval=true)
     */
    private $leftRelations;

    /**
     * @ORM\OneToMany(targetEntity="MetadataRelation", mappedBy="rightTable", cascade={"all"}, orphanRemoval=true)
     */
    private $rightRelations;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="table_name", type="string", length=255, nullable=true)
     */
    private $tableName;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_query", type="text", nullable=true)
     */
    private $customQuery;

    /**
     * @var json
     *
     * @ORM\Column(name="custom_query_fields", type="json", nullable=true)
     */
    private $customQueryFields;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    //--------------------------------------------------------------------------------

    public function __toString()
    {
        if ($this->type != null) {
            if ($this->type == 'table') {
                return $this->tableName;
            }
            return '(' . $this->customQuery . ')';
        }
        return $this->getName() ? $this->getName() : '';
    }

    /**
     * @return string
     */
    public function getSQLName()
    {
        return $this->getId() != null ? ($this->getType() == 'table' ? $this->getTableName() : '(' . $this->getCustomQuery() . ')') : '';
    }

    /**
     * @return string
     */
    public function getSQLAlias()
    {
        return $this->getId() != null ? ('T' . str_replace('-', '_', $this->getId())) : '';
    }
    //--------------------------------------------------------------------------------


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->leftRelations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rightRelations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return MetadataTable
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
     * Set tableName
     *
     * @param string $tableName
     *
     * @return MetadataTable
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * Get tableName
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Set customQuery
     *
     * @param string $customQuery
     *
     * @return MetadataTable
     */
    public function setCustomQuery($customQuery)
    {
        $this->customQuery = $customQuery;

        return $this;
    }

    /**
     * Get customQuery
     *
     * @return string
     */
    public function getCustomQuery()
    {
        return $this->customQuery;
    }

    /**
     * Set customQueryFields
     *
     * @param json $customQueryFields
     *
     * @return MetadataTable
     */
    public function setCustomQueryFields($customQueryFields)
    {
        $this->customQueryFields = $customQueryFields;

        return $this;
    }

    /**
     * Get customQueryFields
     *
     * @return json
     */
    public function getCustomQueryFields()
    {
        return $this->customQueryFields;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return MetadataTable
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
     * Set metadata
     *
     * @param \ TechPromux\DynamicQueryBundle\Entity\Metadata $metadata
     *
     * @return MetadataTable
     */
    public function setMetadata(\ TechPromux\DynamicQueryBundle\Entity\Metadata $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return \ TechPromux\DynamicQueryBundle\Entity\Metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Add field
     *
     * @param \ TechPromux\DynamicQueryBundle\Entity\MetadataField $field
     *
     * @return MetadataTable
     */
    public function addField(\ TechPromux\DynamicQueryBundle\Entity\MetadataField $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Remove field
     *
     * @param \ TechPromux\DynamicQueryBundle\Entity\MetadataField $field
     */
    public function removeField(\ TechPromux\DynamicQueryBundle\Entity\MetadataField $field)
    {
        $this->fields->removeElement($field);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Add leftRelation
     *
     * @param \ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $leftRelation
     *
     * @return MetadataTable
     */
    public function addLeftRelation(\ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $leftRelation)
    {
        $this->leftRelations[] = $leftRelation;

        return $this;
    }

    /**
     * Remove leftRelation
     *
     * @param \ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $leftRelation
     */
    public function removeLeftRelation(\ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $leftRelation)
    {
        $this->leftRelations->removeElement($leftRelation);
    }

    /**
     * Get leftRelations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeftRelations()
    {
        return $this->leftRelations;
    }

    /**
     * Add rightRelation
     *
     * @param \ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $rightRelation
     *
     * @return MetadataTable
     */
    public function addRightRelation(\ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $rightRelation)
    {
        $this->rightRelations[] = $rightRelation;

        return $this;
    }

    /**
     * Remove rightRelation
     *
     * @param \ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $rightRelation
     */
    public function removeRightRelation(\ TechPromux\DynamicQueryBundle\Entity\MetadataRelation $rightRelation)
    {
        $this->rightRelations->removeElement($rightRelation);
    }

    /**
     * Get rightRelations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRightRelations()
    {
        return $this->rightRelations;
    }

    //----------------------------------------------------------------------------------

    public function getName()
    {
        return $this->type != null ? ($this->type == 'table' ? $this->tableName : $this->customQuery) : '';
    }
}
