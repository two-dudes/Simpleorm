<?php

namespace Simpleorm\Mapper\Exception;

/**
 * Class CanNotConnectException
 * @package Models\Mapper
 */
class CanNotConnectException extends MapperException
{
    /**
     *
     */
    function __construct()
    {
        parent::__construct("Can not connect to the database");
    }
}