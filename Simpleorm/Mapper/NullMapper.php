<?php


namespace Simpleorm\Mapper;


use Simpleorm\Model\ModelInterface;

class NullMapper extends AbstractMapper implements MapperInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function fetch($id)
    {
    }

    /**
     * @param array $condition
     * @param array $sort
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function fetchAll(array $condition = array(), array $sort = array(), $limit = null, $offset = null)
    {
    }

    /**
     * @param array $condition
     * @return int
     */
    public function getCount(array $condition = array())
    {
    }

    /**
     * @param array $condition
     * @param array $sort
     * @return mixed
     */
    public function fetchOne(array $condition = null, array $sort = null)
    {
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function delete(ModelInterface $model)
    {
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function update(ModelInterface $model)
    {
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function save(ModelInterface $model)
    {
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    public function create(ModelInterface $model)
    {
    }

    /**
     * @param array $config
     * @return mixed
     */
    public function setConfig(array $config)
    {
    }

    /**
     * @param $modelClassName
     * @return mixed
     */
    public function setModelClassName($modelClassName)
    {
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    protected function doUpdate(ModelInterface $model)
    {
        // TODO: Implement doUpdate() method.
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    protected function doCreate(ModelInterface $model)
    {
        // TODO: Implement doCreate() method.
    }

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    protected function doDelete(ModelInterface $model)
    {
        // TODO: Implement doDelete() method.
    }
}