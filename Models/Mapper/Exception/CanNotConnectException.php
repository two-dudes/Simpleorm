<?php

namespace Simpleorm\Models\Mapper;

/**
 * 
 * @author vin
 *
 */
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