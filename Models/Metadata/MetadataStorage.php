<?php

namespace Models\Metadata;

use Annotations\Parser;
use Annotations\Property;

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
            '@property' => '\Annotations\Property'
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
                    $this->metadata[$modelClass][$annotation->getName()] = array(
                        'type' => $annotation->getType(),
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
        return $this->metadata[$modelClass];
    }

    /**
     * @param $modelClass
     * @return array
     */
    public function getInitialFields($modelClass)
    {
        $this->loadClassMetadata($modelClass);

        $fields = array();
        foreach ($this->metadata[$modelClass] as $name => $config) {
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
        return $this->metadata[$modelClass][$fieldName]['type'];
    }

    /**
     *
     */
    private function __clone()
    {
    }
}