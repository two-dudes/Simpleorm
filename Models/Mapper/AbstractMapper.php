<?php

namespace Models\Mapper;

/**
 *
 * @author vin
 *
 */
use Models\Collection;
use Models\ModelInterface;

/**
 * Class AbstractMapper
 * @package Models\Mapper
 */
abstract class AbstractMapper implements MapperInterface
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * Model class implementing ModelInterface
     * @var string
     */
    protected $modelClassName;

    /**
     * Converts persistent data to entity instance
     *
     * @var string
     */
    protected $modelCollectionClassName = '\Models\Collection';

    /**
     * Key to use for fetching instead id
     *
     * @var mixed $primaryKeyName
     */
    protected $primaryKeyName = 'id';

    /**
     * Array with decorators names
     *
     * @var array $decorators
     */
    protected $decorators = array();

    /**
     * @var array
     */
    protected static $connectionConfig = array();

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    abstract protected function doUpdate(ModelInterface $model);

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    abstract protected function doCreate(ModelInterface $model);

    /**
     * @param ModelInterface $model
     * @return mixed
     */
    abstract protected function doDelete(ModelInterface $model);

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $connectionConfig
     */
    public static function setConnectionConfig(array $connectionConfig)
    {
        self::$connectionConfig = $connectionConfig;
    }

    /**
     * @return array
     */
    public static function getConnectionConfig()
    {
        return self::$connectionConfig;
    }

    /**
     * @param mixed $primaryKeyName
     */
    public function setPrimaryKeyName($primaryKeyName)
    {
        $this->primaryKeyName = $primaryKeyName;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKeyName()
    {
        return $this->primaryKeyName;
    }

    /**
     * @param string $modelCollectionClassName
     */
    public function setModelCollectionClassName($modelCollectionClassName)
    {
        $this->modelCollectionClassName = $modelCollectionClassName;
    }

    /**
     * @return string
     */
    public function getModelCollectionClassName()
    {
        return $this->modelCollectionClassName;
    }

    /**
     * @param string $modelClassName
     */
    public function setModelClassName($modelClassName)
    {
        $this->modelClassName = $modelClassName;
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return $this->modelClassName;
    }

    /**
     * Get mapper decorators
     *
     * @return array
     */
    public function getDecorators()
    {
        return $this->decorators;
    }

    /**
     * Has mapper decorator
     *
     * @param  $name
     * @return bool
     */
    public function hasDecorator($name)
    {
        return in_array($name, $this->decorators);
    }

    /**
     * Add decorator
     *
     * @param  $name
     * @return void
     */
    public function addDecorator($name)
    {
        $this->decorators[] = $name;
    }

    /**
     *
     * @param array $decorators
     * @return null
     */
    public function setDecorators(array $decorators)
    {
        $this->decorators = $decorators;
    }

    /**
     * @param ModelInterface $model
     * @return bool
     */
    public function update(ModelInterface $model)
    {
        $model->beforeUpdate();
        $model->beforeSave();

        $this->doUpdate($model);

        $model->afterUpdate();
        $model->afterSave();
        $model->setClean();

        return true;
    }

    /**
     * @param ModelInterface $model
     * @return bool
     */
    public function create(ModelInterface $model)
    {
        $model->beforeCreate();
        $model->beforeSave();

        $this->doCreate($model);

        $model->afterCreate();
        $model->afterSave();
        $model->setClean();

        return true;
    }

    /**
     * @param ModelInterface $model
     * @return bool
     */
    public function delete(ModelInterface $model)
    {
        $model->beforeDelete();

        $this->doDelete($model);

        $model->afterDelete();

        return true;
    }

    /**
     * @param $data
     * @param null $modelClass
     * @return null
     */
    protected function createModel($data, $modelClass = null)
    {
        if (null === $data || false === $data) {
            return null;
        } else {
            $modelClass = $modelClass ? : $this->getModelClass();
            $data = $this->translateFromStorage($data);
            $model = new $modelClass();
            $model->setCleanData($data);
            return $model;
        }
    }

    /**
     *
     * @param mixed $data
     * @return Collection
     */
    public function createModelCollection($data)
    {
        $objectsData = array();

        if (count($data)) {
            foreach ($data as $model) {
                if (!$model instanceof ModelInterface) {
                    $objectsData[] = $this->createModel($model);
                } else {
                    $objectsData[] = $model;
                }
            }
        }

        return new $this->modelCollectionClassName($objectsData);
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        if (empty($this->modelClassName)) {
            $className = get_class($this);
            if (strpos($className, 'Mapper')) {
                $this->setModelClass(str_replace('Mapper', '', $className));
            }
        }
        return $this->modelClassName;
    }

    /**
     *
     * @param string $className
     * @return MapperInterface
     */
    public function setModelClass($className)
    {
        $this->modelClassName = $className;

        return $this;
    }

    /**
     *
     * @param array $condition
     * @param array $sort
     * @return ModelInterface
     */
    public function fetchOne(array $condition = array(), array $sort = array())
    {
        return $this->fetchAll($condition, $sort, 1)->current();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetch($id)
    {
        return $this->fetchAll(array($this->primaryKeyName => $id), array(), 1)->current();
    }


    /**
     * @param ModelInterface $model
     * @return bool
     */
    public function save(ModelInterface $model)
    {
        if ($model->isNew()) {
            return $this->create($model);
        } else {
            return $this->update($model);
        }
    }

    /**
     * Translate field names in data, got from storage, to model propertiy names
     *
     * @param array $data
     * @return array
     */
    public function translateFromStorage(array $data = array())
    {
        return $data;
    }

    /**
     * Translate field names in data, got from model, to storage fields names
     *
     * @param array $data
     * @return array
     */
    public function translateToStorage(array $data = array())
    {
        return $data;
    }
}