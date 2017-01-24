<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/21/14
 * Time: 3:11 PM
 */

namespace Simpleorm\Mapper;

use Simpleorm\Model\ModelInterface;

/**
 * Interface MapperInterface
 * @package Models\Mapper
 */
interface MapperInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function fetch($id);

    /**
     * @param array $condition
     * @param array $sort
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function fetchAll(array $condition = array(), array $sort = array(), $limit = null, $offset = null);

    /**
     * @param array $condition
     * @return int
     */
    public function getCount(array $condition = array());

    /**
     * @param array $condition
     * @param array $sort
     * @return mixed
     */
    public function fetchOne(array $condition = array(), array $sort = array());

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function delete(ModelInterface $model);

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function update(ModelInterface $model);

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function save(ModelInterface $model);

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function create(ModelInterface $model);

    /**
     * @param array $config
     * @return mixed
     */
    public function setConfig(array $config);

    /**
     * @param $modelClassName
     * @return mixed
     */
    public function setModelClassName($modelClassName);
}