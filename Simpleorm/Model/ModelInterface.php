<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/20/14
 * Time: 6:59 PM
 */

namespace Simpleorm\Model;

/**
 * Class AbstractModel
 * @package Models
 */
/**
 * Interface ModelInterface
 * @package Simpleorm\Models
 */
interface ModelInterface
{
    /**
     * @return mixed
     */
    public function delete();

    /**
     * @return mixed
     */
    public function save();

    /**
     * @return mixed
     */
    public function beforeSave();

    /**
     * @return mixed
     */
    public function beforeUpdate();

    /**
     * @return mixed
     */
    public function beforeCreate();

    /**
     * @return mixed
     */
    public function beforeDelete();

    /**
     * @return mixed
     */
    public function afterSave();

    /**
     * @return mixed
     */
    public function afterCreate();

    /**
     * @return mixed
     */
    public function afterUpdate();

    /**
     * @return mixed
     */
    public function afterDelete();

    /**
     * @return mixed
     */
    public function toArray();

    /**
     * @return mixed
     */
    public function setClean();

    /**
     * @param $data
     * @return mixed
     */
    public function setCleanData($data);

    /**
     * @return mixed
     */
    public function isNew();
}