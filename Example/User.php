<?php
namespace Example;

use Models\AbstractModel;

/**
 * @property int    $id
 * @property string $name
 *
 * @method static \Models\Mapper\Pdo\PdoMapper getMapper() {"table": "user"}
 */
class User extends AbstractModel
{

}