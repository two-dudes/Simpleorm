<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 7:26 PM
 */

namespace Simpleorm\Annotations\Annotation;

/**
 * Class Property
 * @package Annotations
 */
/**
 * Class Property
 * @package Annotations
 */
/**
 * Class Mapper
 * @package Annotations
 */
/**
 * Class MapperMethod
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
    public function getMapperClass()
    {
        return $this->mapperClass;
    }
}