<?php
namespace Example;

use Models\AbstractModel;

/**
 * @property int    $id
 * @property string $name
 *
 * @method static \Models\Mapper\Pdo\PdoMapper getMapper() {"table": "simpleorm.user"}
 */
class User extends AbstractModel
{

}