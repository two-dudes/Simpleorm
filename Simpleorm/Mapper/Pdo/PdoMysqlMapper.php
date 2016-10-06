<?php

namespace Simpleorm\Mapper\Pdo;

use PDO;
use PDOException;
use Simpleorm\Collection\Collection;
use Simpleorm\Mapper\AbstractMapper;
use Simpleorm\Mapper\Exception\CanNotConnectException;
use Simpleorm\Mapper\Exception\MapperException;
use Simpleorm\Model\Mapper\Pdo\QueryBuilder;
use Simpleorm\Model\Mapper\Pdo\StatementBuilder;
use Simpleorm\Model\Mapper\Pdo\StatementException;
use Simpleorm\Model\ModelInterface;

/**
 *
 * @author vin
 *
 */
class PdoMysqlMapper extends AbstractMapper
{
    /**
     * @var Pdo
     */
    protected $connection;

    /**
     * @var bool
     */
    protected $clearNullValues = false;

    /**
     * @var StatementBuilder
     */
    protected $statementBuilder;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     *
     */
    function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
        $this->statementBuilder = new StatementBuilder($this->queryBuilder, $this->getConnection());
    }

    /**
     * @param array $condition
     * @param array $sort
     * @param null $limit
     * @param null $offset
     * @return Collection
     * @throws StatementException
     */
    public function fetchAll(array $condition = array(), array $sort = array(), $limit = null, $offset = null)
    {
        $query = $this->queryBuilder->buildSelectQuery($this->getTableName(), $condition, $sort, $limit, $offset);
        $statement = $this->statementBuilder->prepareAndExecute($query, $condition);
        $result = $statement->fetchAll();
        return $this->createModelCollection($result);
    }

    /**
     * @param array $condition
     * @return int
     * @throws StatementException
     */
    public function getCount(array $condition = array())
    {
        $query = $this->queryBuilder->buildCountQuery($this->getTableName(), $condition);
        $statement = $this->statementBuilder->prepareAndExecute($query, $condition);
        return (int)$statement->fetchColumn();
    }

    /**
     * @param ModelInterface $model
     * @return void
     */
    protected function doDelete(ModelInterface $model)
    {
        $primaryKeyName = $this->getPrimaryKeyName();
        $query = $this->queryBuilder->buildDeleteQuery($this->getTableName(), $primaryKeyName);
        $this->statementBuilder->prepareAndExecute($query, array($primaryKeyName => $model->$primaryKeyName));
    }

    /**
     * @param ModelInterface $model
     * @return void
     * @throws StatementException
     */
    protected function doUpdate(ModelInterface $model)
    {
        $data = $this->translateToStorage($model->toArray());
        $data = $this->filterData($data);

        $primaryKeyName = $this->getPrimaryKeyName();
        $primaryKeyValue = $model->$primaryKeyName;

        $query = $this->queryBuilder->buildUpdateQuery($this->getTableName(), $data, array($primaryKeyName => $primaryKeyValue));
        $this->statementBuilder->prepareAndExecute($query, $data);
    }

    /**
     * @param ModelInterface $model
     * @return void
     * @throws StatementException
     */
    protected function doCreate(ModelInterface $model)
    {
        $primaryKeyName = $this->getPrimaryKeyName();

        $data = $this->translateToStorage($model->toArray());
        $data = $this->filterData($data);
        //unset($data[$primaryKeyName]);

        $query = $this->queryBuilder->buildInsertQuery($this->getTableName(), $data);
        $this->statementBuilder->prepareAndExecute($query, $data);

        $id = (int) $this->getConnection()->lastInsertId();
        if ($id > 0) {
            $model->$primaryKeyName = $id;
        }
    }

    /**
     *
     * @param array $data
     * @return array
     */
    protected function filterData(array $data)
    {
        if ($this->clearNullValues) {
            return array_filter($data, function ($value) {
                return null !== $value;
            });
        } else {
            return $data;
        }
    }

    /**
     * @return mixed
     * @throws MapperException
     */
    protected function getTableName()
    {
        if (!isset($this->config['table'])) {
            throw new MapperException("You must define a table name for mapper " . get_class($this));
        }
        return $this->config['table'];
    }

    /**
     * @return PDO
     * @throws CanNotConnectException
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $connectionParams = self::getConnectionConfig();
            $connectionString = "mysql:host={$connectionParams['host']};port={$connectionParams['port']};dbname={$connectionParams['db']}";
            try {
                $this->connection = new PDO($connectionString, $connectionParams['user'], $connectionParams['password']);
                if (isset($connectionParams['encoding'])) {
                    $this->connection->query("SET NAMES " . $connectionParams['encoding']);
                }
            } catch (PDOException $ex) {
                throw new CanNotConnectException();
            }
        }
        return $this->connection;
    }
}