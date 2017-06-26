<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;

/**
 * MetadataRelation
 *
 * @ORM\Table(name="techpromux_dynamic_query_metadata_relation")
 * @ORM\Entity()
 */
class MetadataRelation extends BaseResource
{
    /**
     * @ORM\ManyToOne(targetEntity="Metadata", inversedBy="relations")
     * @ORM\JoinColumn(name="metadata_id", referencedColumnName="id", nullable=false)
     */
    private $metadata;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataTable", inversedBy="leftRelations")
     * @ORM\JoinColumn(name="left_table_id", referencedColumnName="id", nullable=false)
     */
    private $leftTable;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataTable", inversedBy="rightRelations")
     * @ORM\JoinColumn(name="right_table_id", referencedColumnName="id", nullable=false)
     */
    private $rightTable;

    /**
     * @var string
     *
     * @ORM\Column(name="left_column", type="string", length=255)
     */
    private $leftColumn;

    /**
     * @var string
     *
     * @ORM\Column(name="right_column", type="string", length=255)
     */
    private $rightColumn;

    /**
     * @var string
     *
     * @ORM\Column(name="join_type", type="string", length=255)
     */
    private $joinType;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    //---------------------------------------------------------------------

    public function __toString()
    {
        return $this->getName() ? $this->getName() : '';
    }

    //---------------------------------------------------------------------


    /**
     * Set leftColumn
     *
     * @param string $leftColumn
     *
     * @return MetadataRelation
     */
    public function setLeftColumn($leftColumn)
    {
        $this->leftColumn = $leftColumn;

        return $this;
    }

    /**
     * Get leftColumn
     *
     * @return string
     */
    public function getLeftColumn()
    {
        return $this->leftColumn;
    }

    /**
     * Set rightColumn
     *
     * @param string $rightColumn
     *
     * @return MetadataRelation
     */
    public function setRightColumn($rightColumn)
    {
        $this->rightColumn = $rightColumn;

        return $this;
    }

    /**
     * Get rightColumn
     *
     * @return string
     */
    public function getRightColumn()
    {
        return $this->rightColumn;
    }

    /**
     * Set joinType
     *
     * @param string $joinType
     *
     * @return MetadataRelation
     */
    public function setJoinType($joinType)
    {
        $this->joinType = $joinType;

        return $this;
    }

    /**
     * Get joinType
     *
     * @return string
     */
    public function getJoinType()
    {
        return $this->joinType;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return MetadataRelation
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
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata $metadata
     *
     * @return MetadataRelation
     */
    public function setMetadata(\TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set leftTable
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $leftTable
     *
     * @return MetadataRelation
     */
    public function setLeftTable(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $leftTable)
    {
        $this->leftTable = $leftTable;

        return $this;
    }

    /**
     * Get leftTable
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable
     */
    public function getLeftTable()
    {
        return $this->leftTable;
    }

    /**
     * Set rightTable
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $rightTable
     *
     * @return MetadataRelation
     */
    public function setRightTable(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $rightTable)
    {
        $this->rightTable = $rightTable;

        return $this;
    }

    /**
     * Get rightTable
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable
     */
    public function getRightTable()
    {
        return $this->rightTable;
    }
}
