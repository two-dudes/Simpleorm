<?php
namespace Simpleorm\Tests\Example;

use Simpleorm\Models\AbstractModel;

/**
 * @property int    $id
 * @property string $name
 *
 * @method static Simpleorm\Models\Mapper\Pdo\PdoMapper getMapper() {"table": "simpleorm.user"}
 */
class User extends AbstractModel
{

}