<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/24/14
 * Time: 1:57 PM
 */

namespace Tests\Mappers;

use PHPUnit_Framework_TestCase;
use Simpleorm\Models\AbstractModel;

/**
 * @property int $id
 * @property string $name
 *
 * @method static Simpleorm\Models\Mapper\Pdo\PdoMapper getMapper() {"table" : "user"}
 */
class TestModelAnnotation extends AbstractModel
{
}

/**
 * @property int $id
 * @property string $name
 */
class TestModelProperty extends AbstractModel
{
    protected static $mapperClass = 'Simpleorm\Models\Mapper\Pdo\PdoMapper';
}

class BasicTest extends PHPUnit_Framework_TestCase
{
    public function testAnnotationMapperOk()
    {
        $this->assertInstanceOf('Simpleorm\Models\Mapper\Pdo\PdoMapper', TestModelAnnotation::getMapper());
        $this->assertEquals('user', TestModelAnnotation::getMapper()->getConfig()['table']);
    }
} 