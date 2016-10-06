<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/24/14
 * Time: 1:57 PM
 */

namespace Tests\Mappers;

use Simpleorm\Mapper\Pdo\PdoMysqlMapper;
use Simpleorm\Tests\ModelWithPdoMapper;

class PdoMapperTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        PdoMysqlMapper::setConnectionConfig(array(
            'user' => 'user',
            'password' => 'password',
            'host' => '127.0.0.1',
            'port' => '3306',
            'db'   => 'simpleorm_test'
        ));

        ModelWithPdoMapper::getMapper()->getConnection()->query("DELETE FROM test");
    }

    public function testAll()
    {
        // create
        $user = new ModelWithPdoMapper();
        $user->name = 'test';
        $user->save();

        $userId = $user->id;

        $this->assertGreaterThan(0, $userId);

        // fetch
        $user = ModelWithPdoMapper::getMapper()->fetch($userId);
        $this->assertEquals($user->id, $userId);

        // fetch
        $user = ModelWithPdoMapper::getMapper()->fetchOne(array('id' => $userId));
        $this->assertEquals($user->id, $userId);

        // save
        $user->name = 'test1';
        $user->save();
        $this->assertEquals('test1', ModelWithPdoMapper::getMapper()->fetch($userId)->name);

        // fetch all
        $users = ModelWithPdoMapper::getMapper()->fetchAll(array('id' => $userId));
        $this->assertInstanceOf('Simpleorm\Collection\Collection', $users);
        $this->assertEquals(1, $users->count());

        // delete
        $user->delete();
        $this->assertEquals(0, ModelWithPdoMapper::getMapper()->getCount());
    }
} 