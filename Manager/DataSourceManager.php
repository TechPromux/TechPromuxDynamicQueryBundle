<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 27/05/2017
 * Time: 01:01
 */

namespace TechPromux\Bundle\DynamicQueryBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicQueryBundle\Entity\DataSource;

class DataSourceManager extends BaseResourceManager
{

    /**
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'TechPromuxDynamicQueryBundle';
    }

    /**
     * Get entity class name
     *
     * @return class
     */
    public function getResourceClass()
    {
        return DataSource::class;
    }

    /**
     * Get entity short name
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'DataSource';
    }

    //-------------------------------------------------------------------------------------

    /**
     * @var UtilDynamicQueryManager
     */
    protected $util_dynamic_query_manager;

    /**
     * @return UtilDynamicQueryManager
     */
    public function getUtilDynamicQueryManager()
    {
        return $this->util_dynamic_query_manager;
    }

    /**
     * @param UtilDynamicQueryManager $util_dynamic_query_manager
     * @return DataModelManager
     */
    public function setUtilDynamicQueryManager($util_dynamic_query_manager)
    {
        $this->util_dynamic_query_manager = $util_dynamic_query_manager;
        return $this;
    }

    //-----------------------------------------------------------------------------------------------

    /**
     * Gets "doctrine.dbal.connection_factory" service
     *
     * @return \Doctrine\Bundle\DoctrineBundle\ConnectionFactory
     */
    public function getDoctrineConnectionFactory()
    {
        return $this->service_container->get('doctrine.dbal.connection_factory');
    }

    /**
     * Creates a Doctrine DBAL Connection
     *
     * @param string $driver
     * @param string $host
     * @param string $port
     * @param string $dbname
     * @param string $user
     * @param string $password
     * @return \Doctrine\DBAL\Connection
     */
    public function createDoctrineDBALConnection($driver, $host, $port, $dbname, $user, $password)
    {
        // TODO caching for connection

        $connectionFactory = $this->getDoctrineConnectionFactory();

        $connection = $connectionFactory->createConnection(array(
            'driver' => $driver,
            'host' => $host,
            'port' => $port,
            'dbname' => $dbname,
            'user' => $user,
            'password' => $password,
        ));

        return $connection;
    }

    /**
     * Obtiene el SchemaManager de una conexión
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public function createDoctrineDBALSchemaManager(\Doctrine\DBAL\Connection $connection)
    {

        $em_default = $this->getDoctrineEntityManager();

        $em_new = \Doctrine\ORM\EntityManager::create($connection, $em_default->getConfiguration());

        $schemaManager = $em_new->getConnection()->getSchemaManager();

        return $schemaManager;
    }

    /**
     *
     * @param  DataSource $datasource
     * @return \Doctrine\DBAL\Connection
     */
    public function createDataSourceConnection(DataSource $datasource)
    {

        $encoded_password = $datasource->getDbPassword();
        $plain_password = $this->decodeReversibleString($encoded_password);
        $connection = $this->createDoctrineDBALConnection(
            $datasource->getDriverType(),
            $datasource->getDbHost(),
            $datasource->getDbPort(),
            $datasource->getDbName(),
            $datasource->getDbUser(),
            $plain_password
        );

        return $connection;
    }

    /**
     * Permite crear un $queryBuilder de una conexión
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createQueryBuilderFromConnection($connection)
    {
        return $connection->createQueryBuilder();
    }

    /**
     *
     * @param DataSource $datasource
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilderFromDataSource($datasource)
    {
        return $this->createDataSourceConnection($datasource)->createQueryBuilder();
    }

    /**
     * Permite ejecutar una consulta de tipo SELECT
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @param string $sql
     * @return array
     */
    protected function getSQLResultFromConnection($connection, $sql, array $params = array())
    {
        $result = $connection->fetchAll($sql, $params);
        return $result;
    }

    /**
     *
     * @param DataSource $datasource
     * @param string $sql
     * @return array
     */
    public function getSQLResultFromDataSource($datasource, $sql, array $params = array())
    {
        $connection = $this->createDataSourceConnection($datasource);
        $result = $this->getSQLResultFromConnection($connection, $sql, $params);
        $connection->close();
        return $result;
    }

    /**
     * @param string $driver
     * @param string $host
     * @param string $port
     * @param string $dbname
     * @param string $user
     * @param string $password
     *
     * @return bool
     */
    public function validateConnectionSettings($driver, $host, $port, $dbname, $user, $password)
    {
        try {
            $connection = $this->createDoctrineDBALConnection(
                $driver, $host, $port, $dbname, $user, $password
            );

            if (!$connection || !$connection->getWrappedConnection()) {
                return false;
            } else {
                $connection->close();
                return true;
            }
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }


    /**
     *
     * @param DataSource $object
     * @return array
     */
    protected function getDataSourceMetadataInformation($object)
    {
        $connection = $this->createDataSourceConnection($object);

        $schemaManager = $this->createDoctrineDBALSchemaManager($connection);

        $tables_metadata = array();

        foreach ($schemaManager->listTableNames() as $tableName) {

            $table = $schemaManager->listTableDetails($tableName);
            $columns = $table->getColumns();

            $tables_metadata[$tableName] = array();
            $tables_metadata[$tableName]['name'] = $table->getName(); //$tableName
            $tables_metadata[$tableName]['columns'] = array();

            foreach ($columns as $column) {
                $column_to_array = $column->toArray();
                $class_type = \Doctrine\Common\Util\ClassUtils::getClass($column_to_array['type']);
                $column_to_array['type'] = strtolower(str_replace('Type', '', substr($class_type, strrpos($class_type, '\\') + 1)));
                $column_name = $column_to_array['name'];

                $tables_metadata[$tableName]['columns'][$column_name] = $column_to_array;
            }
        }
        $connection->close();
        return $tables_metadata;
    }


    //-------------------------------------------------------------------------------------------------------

    /**
     * @param DataSource $object
     * @param bool $flushed
     */
    public function prePersist($object, $flushed = true)
    {

        parent::prePersist($object);

        $plain_password = $object->getPlainPassword();
        $encoded_password = $this->encodeReversibleString($plain_password);
        $object->setDbPassword($encoded_password);

        $metadata_information = $this->getDataSourceMetadataInformation($object);
        $object->setMetadataInfo($metadata_information);
    }

    /**
     * @param DataSource $object
     * @param bool $flushed
     *
     * @return \TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource|void
     */
    public function preUpdate($object, $flushed = true)
    {

        parent::preUpdate($object);

        $plain_password = $object->getPlainPassword();
        $encoded_password = $this->encodeReversibleString($plain_password);
        $object->setDbPassword($encoded_password);

        $metadata_information = $this->getDataSourceMetadataInformation($object);
        $object->setMetadataInfo($metadata_information);
    }



}