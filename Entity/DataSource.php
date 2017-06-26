<?php

namespace TechPromux\Bundle\DynamicQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\ResourceOwner;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\HasResourceOwner;
use TechPromux\Bundle\DynamicQueryBundle\Entity\Owner\DynamicQueryResourceOwner;

/**
 * DataSource
 *
 * @ORM\Table(name="techpromux_dynamic_query_datasource")
 * @ORM\Entity()
 */
class DataSource extends BaseResource implements HasResourceOwner
{
    /**
     * @var string
     *
     * @ORM\Column(name="driver_type", type="string", length=255)
     */
    private $driverType;

    /**
     * @var string
     *
     * @ORM\Column(name="db_host", type="string", length=255)
     */
    private $dbHost;

    /**
     * @var string
     *
     * @ORM\Column(name="db_port", type="string", length=255)
     */
    private $dbPort;

    /**
     * @var string
     *
     * @ORM\Column(name="db_name", type="string", length=255)
     */
    private $dbName;

    /**
     * @var string
     *
     * @ORM\Column(name="db_user", type="string", length=255)
     */
    private $dbUser;

    /**
     * @var string
     *
     * @ORM\Column(name="db_password", type="string", length=255)
     */
    private $dbPassword;

    /**
     * @var json
     *
     * @ORM\Column(name="metadata_info", type="json")
     */
    private $metadataInfo;

    /**
     * @ORM\OneToMany(targetEntity="Metadata", mappedBy="datasource", cascade={"all"}, orphanRemoval=true)
     */
    private $metadatas;

    /**
     * @var DynamicQueryResourceOwner
     *
     * @ORM\ManyToOne(targetEntity="\TechPromux\Bundle\DynamicQueryBundle\Entity\Owner\DynamicQueryResourceOwner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true)
     */
    protected $owner;

    //--------------------------------------------------------

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var boolean
     */
    private $blankPassword;

    //--------------------------------------------------------

    public function __toString()
    {
        return $this->getName() ? $this->getName() : '';
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return DataSource
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBlankPassword()
    {
        return $this->blankPassword;
    }

    /**
     * @param bool $blankPassword
     * @return DataSource
     */
    public function setBlankPassword($blankPassword)
    {
        $this->blankPassword = $blankPassword;
        return $this;
    }

    //-------------------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metadatas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set driverType
     *
     * @param string $driverType
     *
     * @return DataSource
     */
    public function setDriverType($driverType)
    {
        $this->driverType = $driverType;

        return $this;
    }

    /**
     * Get driverType
     *
     * @return string
     */
    public function getDriverType()
    {
        return $this->driverType;
    }

    /**
     * Set dbHost
     *
     * @param string $dbHost
     *
     * @return DataSource
     */
    public function setDbHost($dbHost)
    {
        $this->dbHost = $dbHost;

        return $this;
    }

    /**
     * Get dbHost
     *
     * @return string
     */
    public function getDbHost()
    {
        return $this->dbHost;
    }

    /**
     * Set dbPort
     *
     * @param string $dbPort
     *
     * @return DataSource
     */
    public function setDbPort($dbPort)
    {
        $this->dbPort = $dbPort;

        return $this;
    }

    /**
     * Get dbPort
     *
     * @return string
     */
    public function getDbPort()
    {
        return $this->dbPort;
    }

    /**
     * Set dbName
     *
     * @param string $dbName
     *
     * @return DataSource
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;

        return $this;
    }

    /**
     * Get dbName
     *
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * Set dbUser
     *
     * @param string $dbUser
     *
     * @return DataSource
     */
    public function setDbUser($dbUser)
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    /**
     * Get dbUser
     *
     * @return string
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * Set dbPassword
     *
     * @param string $dbPassword
     *
     * @return DataSource
     */
    public function setDbPassword($dbPassword)
    {
        $this->dbPassword = $dbPassword;

        return $this;
    }

    /**
     * Get dbPassword
     *
     * @return string
     */
    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    /**
     * Set metadataInfo
     *
     * @param json $metadataInfo
     *
     * @return DataSource
     */
    public function setMetadataInfo($metadataInfo)
    {
        $this->metadataInfo = $metadataInfo;

        return $this;
    }

    /**
     * Get metadataInfo
     *
     * @return json
     */
    public function getMetadataInfo()
    {
        return $this->metadataInfo;
    }

    /**
     * Add metadata
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata $metadata
     *
     * @return DataSource
     */
    public function addMetadata(\TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata $metadata)
    {
        $this->metadatas[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata $metadata
     */
    public function removeMetadata(\TechPromux\Bundle\DynamicQueryBundle\Entity\Metadata $metadata)
    {
        $this->metadatas->removeElement($metadata);
    }

    /**
     * Get metadatas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadatas()
    {
        return $this->metadatas;
    }


    /**
     * Set owner
     *
     * @param DynamicQueryResourceOwner $owner
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
     * @return DynamicQueryResourceOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
