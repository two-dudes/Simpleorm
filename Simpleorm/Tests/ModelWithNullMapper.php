<?php

namespace Simpleorm\Tests;

use Simpleorm\Mapper\NullMapper;
use Simpleorm\Model\AbstractModel;

/**
 * @property int $id
 * @property string $name {"default": "John"}
 *
 * @method static NullMapper getMapper()  {"table": "users"}
 */
class ModelWithNullMapper extends AbstractModel
{
}