<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 6:59 PM
 */

namespace Simpleorm\Models;

use Simpleorm\Annotations\Property;

/**
 * Class AbstractModel
 * @package Models
 */
interface ModelInterface
{
    public function delete();

    public function save();

    public function beforeSave();

    public function beforeUpdate();

    public function beforeCreate();

    public function beforeDelete();

    public function afterSave();

    public function afterCreate();

    public function afterUpdate();

    public function afterDelete();

    public function toArray();

    public function setClean();

    public function setCleanData($data);

    public function isNew();
}