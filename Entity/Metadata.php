<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\ResourceOwner;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\HasResourceOwner;

/**
 * Metadata
 *
 * @ORM\Table(name="techpromux_dynamic_query_metadata")
 * @ORM\Entity(")
 */
class Metadata extends BaseResource implements HasResourceOwner
{
    /**
     * @ORM\ManyToOne(targetEntity="DataSource", inversedBy="metadatas")
     * @ORM\JoinColumn(name="datasource_id", referencedColumnName="id", nullable=false)
     */
    private $datasource;

    /**
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="MetadataTable", mappedBy="metadata", cascade={"all"}, orphanRemoval=true)
     */
    private $tables;

    /**
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="MetadataRelation", mappedBy="metadata", cascade={"all"}, orphanRemoval=true)
     */
    private $relations;

    /**
     * @ORM\OrderBy({"position" = "ASC"})
     * @ORM\OneToMany(targetEntity="MetadataField", mappedBy="metadata", cascade={"all"}, orphanRemoval=true)
     */
    private $fields;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="DataModel", mappedBy="metadata", cascade={"all"}, orphanRemoval=true)
     */
    private $datamodels;

    /**
     * @var ResourceOwner
     *
     * @ORM\ManyToOne(targetEntity="TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\ResourceOwner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true)
     */
    protected $owner;

    //-----------------------------------------------------------------------------------------

    public function __toString()
    {
        return $this->getName() ? $this->getName() : '';
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

    public function getDataSourceTitleMetadataTitleAndEnabled()
    {
        return $this->getDatasource()->getName() . ' -> ' . $this->getTitle() . ($this->getEnabled() ? '' : ' (x)');
    }

    //--------------------------------------------------------------------------


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tables = new \Doctrine\Common\Collections\ArrayCollection();
        $this->relations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->datamodels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set datasource
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataSource $datasource
     *
     * @return Metadata
     */
    public function setDatasource(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataSource $datasource)
    {
        $this->datasource = $datasource;

        return $this;
    }

    /**
     * Get datasource
     *
     * @return \TechPromux\Bundle\DynamicQueryBundle\Entity\DataSource
     */
    public function getDatasource()
    {
        return $this->datasource;
    }

    /**
     * Add table
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $table
     *
     * @return Metadata
     */
    public function addTable(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $table)
    {
        $this->tables[] = $table;

        return $this;
    }

    /**
     * Remove table
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $table
     */
    public function removeTable(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataTable $table)
    {
        $this->tables->removeElement($table);
    }

    /**
     * Get tables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Add relation
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation $relation
     *
     * @return Metadata
     */
    public function addRelation(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation $relation)
    {
        $this->relations[] = $relation;

        return $this;
    }

    /**
     * Remove relation
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation $relation
     */
    public function removeRelation(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataRelation $relation)
    {
        $this->relations->removeElement($relation);
    }

    /**
     * Get relations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Add field
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $field
     *
     * @return Metadata
     */
    public function addField(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Remove field
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $field
     */
    public function removeField(\TechPromux\Bundle\DynamicQueryBundle\Entity\MetadataField $field)
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
     * Add datamodel
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel $datamodel
     *
     * @return Metadata
     */
    public function addDatamodel(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel $datamodel)
    {
        $this->datamodels[] = $datamodel;

        return $this;
    }

    /**
     * Remove datamodel
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel $datamodel
     */
    public function removeDatamodel(\TechPromux\Bundle\DynamicQueryBundle\Entity\DataModel $datamodel)
    {
        $this->datamodels->removeElement($datamodel);
    }

    /**
     * Get datamodels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatamodels()
    {
        return $this->datamodels;
    }
}
