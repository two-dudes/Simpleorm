<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 4/9/14
 * Time: 5:04 PM
 */

namespace Simpleorm\Tests\Model;

use Simpleorm\Tests\ModelWithNullMapper;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function testMagicGetSet()
    {
        $model = new ModelWithNullMapper();
        $model->setName('testName');
        $this->assertEquals($model->name, 'testName');
        $this->assertEquals($model->getName(), 'testName');
    }
}