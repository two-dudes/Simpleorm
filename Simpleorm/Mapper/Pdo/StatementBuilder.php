<?php

namespace Simpleorm\Mapper\Pdo;

use PDO;

/**
 * Class StatementBuilder
 * @package Models\Mapper\Pdo
 */
class StatementBuilder
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @param QueryBuilder $builder
     * @param PDO $connection
     */
    function __construct(QueryBuilder $builder, PDO $connection)
    {
        $this->queryBuilder = new QueryBuilder();
        $this->connection = $connection;
    }

    /**
     * @param $query
     * @param array $condition
     * @return \PDOStatement
     * @throws StatementException
     */
    public function prepareAndExecute($query, array $condition = array())
    {
        if (!$statement = $this->connection->prepare($query)) {
            throw new StatementException($statement);
        }

        if (!$statement->execute($this->getConditionForStatement($condition))) {
            throw new StatementException($statement);
        }

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement;
    }

    /**
     * @param array $condition
     * @return array
     */
    protected function getConditionForStatement(array $condition = array())
    {
        $statementCondition = array();
        if (!empty($condition)) {
            foreach ($condition as $k => $v) {
                $statementCondition[":$k"] = $v;
            }
        }
        return $statementCondition;
    }
}