<?php

namespace Simpleorm\Model\Mapper\Pdo;

class QueryBuilder
{
    /**
     * @param $tableName
     * @param array $condition
     * @param array $sort
     * @param $limit
     * @param $offset
     * @return string
     */
    public function buildSelectQuery($tableName, array $condition = array(), array $sort = array(), $limit = null, $offset = null)
    {
        $query = 'SELECT * FROM ' . $tableName . ' ';
        $query .= $this->buildCondition($condition);
        $query .= $this->buildSort($sort);
        $query .= $this->buildLimitOffset($limit, $offset);
        return $query;
    }

    /**
     * @param $tableName
     * @param array $condition
     * @return string
     */
    public function buildCountQuery($tableName, array $condition = array())
    {
        $query = 'SELECT count(*) FROM ' . $tableName . ' ';
        $query .= $this->buildCondition($condition);
        return $query;
    }

    /**
     * @param $tableName
     * @param array $data
     * @return string
     */
    public function buildInsertQuery($tableName, array $data)
    {
        $query = 'INSERT INTO ' . $tableName . ' ';
        $query .= '(' . implode(',', array_map(function ($val) {
                return "`$val`";
            }, array_keys($data))) . ')';
        $query .= ' VALUES ';
        $query .= '(' . implode(',', array_map(function ($val) {
                return ":$val";
            }, array_keys($data))) . ')';
        return $query;
    }

    /**
     * @param $tableName
     * @param array $data
     * @param array $condition
     * @return string
     */
    public function buildUpdateQuery($tableName, array $data, array $condition)
    {
        $query = 'UPDATE ' . $tableName . ' SET ';
        $query .= $this->buildFields($data);
        $query .= $this->buildCondition($condition);
        return $query;
    }

    /**
     * @param $tableName
     * @param $primaryKeyName
     * @return string
     */
    public function buildDeleteQuery($tableName, $primaryKeyName)
    {
        return 'DELETE FROM ' . $tableName . ' WHERE `' . $primaryKeyName . '` = :' . $primaryKeyName;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function buildFields(array $data)
    {
        return implode(', ', array_map(function ($value) {
            return "`$value` = :$value";
        }, array_keys($data)));
    }

    /**
     * @param $limit
     * @param $offset
     * @return string
     */
    protected function buildLimitOffset($limit, $offset)
    {
        $string = '';
        if (!empty($limit)) {
            $string .= ' LIMIT ' . $limit;
        }
        if (!empty($offset)) {
            $string .= ' OFFSET ' . $offset;
        }
        return $string;
    }

    /**
     * @param array $condition
     * @return string
     */
    protected function buildCondition(array $condition)
    {
        $string = '';
        if (!empty($condition)) {
            $string .= ' WHERE ';
            $whereParts = array();
            foreach ($condition as $key => $value) {
                $whereParts[] = "$key = :$key";
            }
            $string .= implode(' AND ', $whereParts);
        }
        return $string;
    }

    /**
     * @param array $sort
     * @return string
     */
    protected function buildSort(array $sort)
    {
        $string = '';
        if (!empty($sort)) {
            $string .= " ORDER BY ";
            $sortParts = array();
            foreach ($sort as $key => $value) {
                $sortParts[] = "$key " . ($value == 'ASC' ? 'ASC' : 'DESC');
            }
            $string .= implode(', ', $sortParts);
        }
        return $string;
    }
}