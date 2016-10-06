<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/24/14
 * Time: 1:57 PM
 */

namespace Simpleorm\Tests\Mappers;

use PHPUnit_Framework_TestCase;
use Simpleorm\Mapper\Pdo\PdoMysqlMapper;
use Simpleorm\Tests\ModelWithNullMapper;

class BasicTest extends PHPUnit_Framework_TestCase
{
    public function testAnnotationMapperOk()
    {
        $this->assertInstanceOf('Simpleorm\Mapper\NullMapper', ModelWithNullMapper::getMapper());
    }
}

class UserMapper extends PdoMysqlMapper
{
    public function fetchComplicatedReport($someParams)
    {
        $stmt = $this->getConnection()->prepare('...');
        $stmt->execute();

        return $stmt->fetchAll();
    }
}