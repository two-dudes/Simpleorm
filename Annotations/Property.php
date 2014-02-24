<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 7:26 PM
 */

namespace Annotations;

/**
 * Class Property
 * @package Annotations
 */
/**
 * Class Property
 * @package Annotations
 */
class Property
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $params
     * @param $line
     */
    function __construct(array $params, $line)
    {
        $this->name = str_replace('$', '', $params[2]);
        $this->type = $params[1];

        if (false !== strpos($line, '{')) {
            $this->options = (array) json_decode(substr($line, strpos($line, '{'), strpos($line, '}')));
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}