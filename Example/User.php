<?php
namespace Example;
use Models\AbstractModel;

/**
 * @property int    $id
 * @property string $name
 *
 * @method static UserMapper getMapper()
 *
 */
class User extends AbstractModel
{
    protected static $mapperClass = '\Example\UserMapper';
}