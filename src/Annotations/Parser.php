<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 6:58 PM
 */

namespace Annotations;

use Annotations\Exception\AnnotationClassNotFound;

/**
 * Class Parser
 * @package Annotations
 */
class Parser
{
    /**
     * @var array
     */
    protected $map;

    /**
     * @param mixed $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @param $className
     * @return array
     */
    public function parseClass($className)
    {
        $reflection = new \ReflectionClass($className);
        $comment = $reflection->getDocComment();

        $lines = explode("\n", $comment);

        $annotations = array();
        foreach ($lines as $line) {
            if (false !== strpos($line, '@')) {
                $annotation = $this->parseAnnotation($line);
                if ($annotation) {
                    $annotations[] = $annotation;
                }
            }
        }

        return $annotations;
    }

    /**
     * @param $line
     * @return mixed
     * @throws Exception\AnnotationClassNotFound
     */
    protected function parseAnnotation($line)
    {
        $line = trim($line, ' *');
        $parts = preg_split('/(?<!{)\s+(?!})/', $line);

        $annotationName = $parts[0];

        if (isset($this->map[$annotationName])) {
            $annotationClass = $this->map[$annotationName];
            if (!class_exists($annotationClass)) {
                throw new AnnotationClassNotFound();
            }
            $annotation = new $annotationClass($parts, $line);
            $annotation->parseOptions($line);
            return $annotation;
        }
    }
}