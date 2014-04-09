<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 4/9/14
 * Time: 5:04 PM
 */

namespace Tests\Model;
use PHPUnit_Framework_TestCase;
use Tests\Example\User;

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function testMagicGetSet()
    {
        $model = new User();
        $model->setName('testName');
        $this->assertEquals($model->name, 'testName');
        $this->assertEquals($model->getName(), 'testName');
    }
}