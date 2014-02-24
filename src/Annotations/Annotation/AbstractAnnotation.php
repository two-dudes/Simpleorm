<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 6:58 PM
 */

namespace Annotations\Annotation;

/**
 * Class Parser
 * @package Annotations
 */
abstract class AbstractAnnotation
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param $line
     */
    public function parseOptions($line)
    {
        if (false !== strpos($line, '{')) {
            $this->options = (array) json_decode(substr($line, strpos($line, '{'), strpos($line, '}')));
        }
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}