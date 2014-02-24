<?php
/**
 * Created by PhpStorm.
 * User: vin
 * Date: 2/21/14
 * Time: 3:11 PM
 */

namespace Models\Mapper;

use Models\ModelInterface;

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
    public function fetchOne(array $condition = null, array $sort = null);

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
}