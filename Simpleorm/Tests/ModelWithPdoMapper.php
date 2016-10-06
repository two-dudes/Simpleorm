<?php
namespace Simpleorm\Tests;

use Simpleorm\Mapper\Pdo\PdoMysqlMapper;
use Simpleorm\Model\AbstractModel;

/**
 * @property int    $id
 * @property string $name
 *
 * @method static PdoMysqlMapper getMapper() {"table": "test"}
 */
class ModelWithPdoMapper extends AbstractModel
{

}