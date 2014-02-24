<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/24/14
 * Time: 1:57 PM
 */

namespace Tests\Mappers;

use Example\User;
use Models\Mapper\Pdo\PdoMapper;
use PHPUnit_Framework_TestCase;

class PdoMapperTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        User::getMapper()->getConnection()->query("DELETE FROM user");
    }

    public function testAll()
    {
        // create
        $user = new User();
        $user->name = 'test';
        $user->save();

        $userId = $user->id;

        $this->assertGreaterThan(0, $userId);

        // fetch
        $user = User::getMapper()->fetch($userId);
        $this->assertEquals($user->id, $userId);

        // save
        $user->name = 'test1';
        $user->save();
        $this->assertEquals('test1', User::getMapper()->fetch($userId)->name);

        // fetch all
        $users = User::getMapper()->fetchAll(array('id' => $userId));
        $this->assertInstanceOf('Models\Collection', $users);
        $this->assertEquals(1, $users->count());

        // delete
        $user->delete();
        $this->assertEquals(0, User::getMapper()->getCount());
    }
} 