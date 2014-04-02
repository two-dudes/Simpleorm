<?php

namespace Simpleorm\Models\Metadata;

use Simpleorm\Annotations\Annotation\MapperMethod;
use Simpleorm\Annotations\Annotation\Property;
use Simpleorm\Annotations\Parser;

/**
 * Class MetadataStorage. Stores model properties and options
 *
 * @package Models\Metadata
 */
class MetadataStorage
{
    /**
     * @var array
     */
    protected $metadata = array();

    /**
     * @var Parser
     */
    protected $annotationsParser;

    /**
     * @var MetadataStorage
     */
    protected static $instance;

    /**
     * @return MetadataStorage
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     *
     */
    private function __construct()
    {
        $parser = new Parser();
        $parser->setMap(array(
            '@property' => 'Simpleorm\Annotations\Annotation\Property',
            '@method' => 'Simpleorm\Annotations\Annotation\MapperMethod',
        ));

        $this->annotationsParser = $parser;
    }

    /**
     * @param string $modelClass
     */
    protected function loadClassMetadata($modelClass)
    {
        if (empty($this->metadata[$modelClass])) {
            $this->metadata[$modelClass] = array();
            $annotations = $this->annotationsParser->parseClass($modelClass);

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Property) {
                    if (!is_array($this->metadata[$modelClass])) {
                        $this->metadata[$modelClass] = array(
                            'fields' => array(),
                            'mapperClass' => false
                        );
                    }
                    $this->metadata[$modelClass]['fields'][$annotation->getName()] = array(
                        'type' => $annotation->getType(),
                        'options' => $annotation->getOptions()
                    );
                } else if ($annotation instanceof MapperMethod) {
                    $this->metadata[$modelClass]['mapper'] = array(
                        'class' => $annotation->getMapperClass(),
                        'options' => $annotation->getOptions()
                    );
                }
            }
        }
    }

    /**
     * @param $modelClass
     * @return mixed
     */
    public function getModelFieldsMetadata($modelClass)
    {
        $this->loadClassMetadata($modelClass);
        return $this->metadata[$modelClass]['fields'];
    }

    /**
     * @param $modelClass
     * @return array
     */
    public function getInitialFields($modelClass)
    {
        $this->loadClassMetadata($modelClass);

        $fields = array();
        foreach ($this->metadata[$modelClass]['fields'] as $name => $config) {
            if (isset($config['options']) && isset($config['options']['default'])) {
                $fields[$name] = $config['options']['default'];
            } else {
                $fields[$name] = null;
            }
        }

        return $fields;
    }

    /**
     * @param $modelClass
     * @param $fieldName
     * @return mixed
     */
    public function getFieldType($modelClass, $fieldName)
    {
        $this->loadClassMetadata($modelClass);
        return $this->metadata[$modelClass]['fields'][$fieldName]['type'];
    }

    /**
     * @param $modelClass
     * @return array|false
     */
    public function getMapperConfig($modelClass)
    {
        $this->loadClassMetadata($modelClass);
        if (isset($this->metadata[$modelClass]['mapper'])) {
            return $this->metadata[$modelClass]['mapper'];
        } else {
            return false;
        }
    }

    /**
     *
     */
    private function __clone()
    {
    }
}