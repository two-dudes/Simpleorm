<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 7:26 PM
 */

namespace Simpleorm\Annotations\Annotation;
use Simpleorm\Annotations\ExtendedReflectionClass;

/**
 * Class Property
 * @package Annotations
 */
class MapperMethod extends AbstractAnnotation
{
    /**
     * @var
     */
    protected $mapperClass;

    /**
     * @param array $params
     * @param $line
     */
    function __construct(array $params, $line)
    {
        $this->mapperClass = $params[2];
    }

    /**
     * @return mixed
     */
    public function getMapperClass($modelClass)
    {
        if (!class_exists($this->mapperClass)) {
            $reflectionClass = new ExtendedReflectionClass($modelClass);
            $statements = $reflectionClass->getUseStatements();

            foreach ($statements as $statement) {
                $class = $statement['class'];
                $parts = explode('\\', $class);
                if (end($parts) == $this->mapperClass) {
                    $this->mapperClass = $class;
                    break;
                }
            }
        }

        return $this->mapperClass;
    }
}