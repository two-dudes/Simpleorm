<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 6:59 PM
 */

namespace Models;

use Models\Mapper\MapperInterface;
use Models\Metadata\MetadataStorage;

/**
 * Class AbstractModel
 * @package Models
 */
class AbstractModel implements ModelInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $cleanData = array();

    /**
     * @var Metadata\MetadataStorage
     */
    protected $metadataStorage;

    /**
     * @var MapperInterface
     */
    protected static $mapper;

    /**
     * @var string
     */
    protected static $mapperClass;

    /**
     *
     */
    function __construct(array $data = array())
    {
        $this->metadataStorage = MetadataStorage::getInstance();
        $this->data = $this->metadataStorage->getInitialFields(get_class($this));
        $this->populate($data);;
    }

    /**
     * @return MapperInterface
     */
    public static function getMapper()
    {
        if (null === self::$mapper) {
            self::$mapper = new static::$mapperClass;
        }
        return self::$mapper;
    }

    /**
     * @param array $data
     */
    public function populate(array $data)
    {
        foreach (array_keys($this->data) as $fieldName) {
            $this->$fieldName = isset($data[$fieldName]) ? $data[$fieldName] : null;
        }
    }


    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        if (!$this->isPropertyExists($name)) {
            throw new \Exception("Property $name does not exist in model");
        }

        return $this->data[$name] = $this->convertPropertyValue($name, $value);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    private function convertPropertyValue($name, $value)
    {
        settype($value, $this->metadataStorage->getFieldType(get_class($this), $name));
        return $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isPropertyExists($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return empty($this->cleanData);
    }

    /**
     *  It means that model is from storage
     */
    public function setClean()
    {
        $this->cleanData = $this->data;
    }

    /**
     * @param $data
     */
    public function setCleanData($data)
    {
        $this->populate($data);
        $this->setClean();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }


    /**
     *
     */
    public function beforeSave()
    {

    }

    /**
     *
     */
    public function beforeCreate()
    {

    }

    /**
     *
     */
    public function beforeUpdate()
    {

    }

    /**
     *
     */
    public function beforeDelete()
    {

    }

    /**
     *
     */
    public function afterSave()
    {

    }

    /**
     *
     */
    public function afterCreate()
    {
    }

    /**
     *
     */
    public function afterUpdate()
    {
    }

    /**
     *
     */
    public function afterDelete()
    {
    }

    /**
     *
     */
    public function delete()
    {
        return self::getMapper()->delete($this);
    }

    /**
     * @return mixed
     */
    public function save()
    {
        return self::getMapper()->save($this);
    }
}