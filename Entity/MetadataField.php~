<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;

/**
 * MetadataField
 *
 * @ORM\Table(name="techpromux_dynamic_query_metadata_field")
 * @ORM\Entity()
 */
class MetadataField extends BaseResource
{
    /**
     *
     * @ORM\ManyToOne(targetEntity="Metadata", inversedBy="fields")
     * @ORM\JoinColumn(name="metadata_id", referencedColumnName="id", nullable=false)
     */
    private $metadata;

    /**
     * @ORM\ManyToOne(targetEntity="MetadataTable", inversedBy="fields")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id", nullable=false)
     */
    private $table;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DataModelDetail", mappedBy="field", cascade={"all"}, orphanRemoval=true)
     */
    private $details;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DataModelGroup", mappedBy="field", cascade={"all"}, orphanRemoval=true)
     */
    private $groups;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DataModelCondition", mappedBy="field", cascade={"all"}, orphanRemoval=true)
     */
    private $conditions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DataModelOrder", mappedBy="field", cascade={"all"}, orphanRemoval=true)
     */
    private $orders;


    //------------------------------------------------------------------------------

    public function __toString()
    {
        return $this->getName() ? $this->getName() : '';
    }

    //--------------------------------------------------------------------------------------

    public function getTableWithFieldNames()
    {
        $table_name = $this->table != null ? $this->table->getTitle() : '?';
        return '[' . $table_name . ']' . '.' . ($this->getName() != null ? $this->getName() : '?');
    }

    public function getSQLName()
    {
        $table = $this->getTable();

        $table_sql_alias = $table->getSQLAlias();

        $field_name = $this->getName();

        $sql_name = $table_sql_alias . '.' . $field_name;

        return $sql_name;
    }

    //-------------------------------------------------------------------------------


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->details = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->conditions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return MetadataField
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
     * @return MetadataField
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
     * @return MetadataField
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
     * Set table
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $table
     *
     * @return MetadataField
     */
    public function setTable(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Add detail
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelDetail $detail
     *
     * @return MetadataField
     */
    public function addDetail(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelDetail $detail)
    {
        $this->details[] = $detail;

        return $this;
    }

    /**
     * Remove detail
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelDetail $detail
     */
    public function removeDetail(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelDetail $detail)
    {
        $this->details->removeElement($detail);
    }

    /**
     * Get details
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Add group
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelGroup $group
     *
     * @return MetadataField
     */
    public function addGroup(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelGroup $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelGroup $group
     */
    public function removeGroup(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelGroup $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add condition
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelCondition $condition
     *
     * @return MetadataField
     */
    public function addCondition(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelCondition $condition)
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Remove condition
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelCondition $condition
     */
    public function removeCondition(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelCondition $condition)
    {
        $this->conditions->removeElement($condition);
    }

    /**
     * Get conditions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Add order
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelOrder $order
     *
     * @return MetadataField
     */
    public function addOrder(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelOrder $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelOrder $order
     */
    public function removeOrder(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelOrder $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
