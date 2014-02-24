<?php

namespace Models\Mapper\Pdo;

use Models\Mapper\MapperException;

/**
 * Class StatementException
 * @package Models\Mapper\Pdo
 */
class StatementException extends MapperException
{
    /**
     * @param \PDOStatement $stmt
     */
    function __construct(\PDOStatement $stmt)
    {
        parent::__construct('Statement error, code : ' . $stmt->errorCode() . ', info: ' . implode("\n", $stmt->errorInfo()));
    }
}