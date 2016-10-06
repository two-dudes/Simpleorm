<?php

/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/24/14
 * Time: 1:57 PM
 */

namespace Tests;

use PHPUnit_Framework_TestCase;
use Simpleorm\Collection\Collection;
use Simpleorm\Tests\ModelWithNullMapper;


/**
 * Class PdoMapperTest
 * @package Tests
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     *
     */
    protected function setUp()
    {
        $this->collection = new Collection();
        $this->collection->append(new ModelWithNullMapper(array('name' => 'vasily', 'id' => 1)));
        $this->collection->append(new ModelWithNullMapper(array('name' => 'andrew', 'id' => 2)));
        $this->collection->append(new ModelWithNullMapper(array('name' => 'piotr', 'id' => 3)));
    }

    /**
     *
     */
    public function testGetFirst()
    {
        $this->assertEquals(1, $this->collection->getFirst()->id);
    }

    /**
     *
     */
    public function testGetLast()
    {
        $this->assertEquals(3, $this->collection->getLast()->id);
    }

    /**
     *
     */
    public function testCount()
    {
        $this->assertEquals(3, $this->collection->count());
    }

    /**
     *
     */
    public function testGetFieldSum()
    {
        $this->assertEquals(6, $this->collection->getFieldSum('id'));
    }

    /**
     *
     */
    public function testToArray()
    {
        $this->assertInternalType('array', $this->collection->toArray());
        $this->assertEquals(3, count($this->collection->toArray()));
    }

    /**
     *
     */
    public function testExtractField()
    {
        $this->assertEquals(array(1, 2, 3), $this->collection->extractField('id'));
    }

    /**
     *
     */
    public function testGetPairs()
    {
        $this->assertEquals(array(1 => 'vasily', 2 => 'andrew', 3 => 'piotr'), $this->collection->getPairs('id', 'name'));
    }

    /**
     *
     */
    public function testGetGrouppedByKeys()
    {
        $grouped = $this->collection->getGrouppedByKey('id');
        $this->assertEquals(array(1, 2, 3), array_keys($grouped));
    }

    /**
     *
     */
    public function testCollectItemsByCond()
    {
        $collection = $this->collection->collectItemsByCond(array('id' => array(1, 2)));
        $this->assertEquals(2, $collection->count());
    }
} 