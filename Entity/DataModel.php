<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\HasResourceOwner;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\ResourceOwner;

/**
 * DataModel
 *
 * @ORM\Table(name="techpromux_dynamic_query_datamodel")
 * @ORM\Entity()
 */
class DataModel extends BaseResource implements HasResourceOwner
{
    /**
     * @ORM\ManyToOne(targetEntity="Metadata", inversedBy="datamodels")
     * @ORM\JoinColumn(name="metadata_id", referencedColumnName="id", nullable=false)
     */
    private $metadata;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="DataModelDetail", mappedBy="datamodel", cascade={"all"}, orphanRemoval=true)
     */
    private $details;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="DataModelGroup", mappedBy="datamodel", cascade={"all"}, orphanRemoval=true)
     */
    private $groups;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="DataModelCondition", mappedBy="datamodel", cascade={"all"}, orphanRemoval=true)
     */
    private $conditions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="DataModelOrder", mappedBy="datamodel", cascade={"all"}, orphanRemoval=true)
     */
    private $orders;
    
    /**
     * @var ResourceOwner
     *
     * @ORM\ManyToOne(targetEntity="TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\ResourceOwner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true)
     */
    protected $owner;


    //-------------------------------------------------------------------

    public function __toString()
    {
        return $this->getTitle() ? $this->getTitle() : '';
    }

    /**
     * Set owner
     *
     * @param ResourceOwner $owner
     *
     * @return DataSource
     */
    public function setOwner(ResourceOwner $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return ResourceOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    //--------------------------------------------------------------------------------


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
     * Set metadata
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata $metadata
     *
     * @return DataModel
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
     * Add detail
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModelDetail $detail
     *
     * @return DataModel
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
     * @return DataModel
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
     * @return DataModel
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
     * @return DataModel
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
