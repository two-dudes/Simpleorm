<?php

namespace Models\Mapper;
use Models\Metadata\MetadataStorage;

/**
 * 
 * @author vin
 *
 */
class MapperManager
{
    /**
     * @var MapperManager
     */
    protected static $instance;

    /**
     * @var array
     */
    protected $storage = array();

    /**
     * @return MapperManager
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param $modelClass
     * @return mixed
     * @throws MapperException
     */
    public function getMapper($modelClass)
    {
        if (!isset($this->storage[$modelClass])) {
            $mapperConfig = MetadataStorage::getInstance()->getMapperConfig($modelClass);

            if (empty($mapperConfig['class']) || !class_exists($mapperConfig['class'])) {
                throw new MapperException('Mapper class ' . $mapperConfig['class'] . ' not found');
            }

            $mapper = new $mapperConfig['class'];
            /** @var MapperInterface $mapper */
            $mapper->setConfig($mapperConfig['options']);
            $mapper->setModelClassName($modelClass);

            $this->storage[$modelClass] = $mapper;
        }
        return $this->storage[$modelClass];
    }

    /**
     *
     */
    private function __construct(){}

    /**
     *
     */
    private function __clone(){}
}