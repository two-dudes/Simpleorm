<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 6:59 PM
 */

namespace Example;
use Models\Mapper\Pdo\PdoMapper;

/**
 * @method User fetch($id)
 */
class UserMapper extends PdoMapper
{
    protected $config = array('table' => 'user');
}