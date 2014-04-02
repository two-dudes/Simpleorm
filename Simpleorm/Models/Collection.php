<?php

namespace Simpleorm\Models;

use Simpleorm\Models\Collection\CollectionException;

/**
 *
 * @author vin
 *
 */
class Collection implements \Countable, \arrayAccess, \Iterator
{
    /**
     * @var string
     */
    protected $modelClassName = 'ModelInterface';

    /**
     * Container for model entities
     *
     * @var $data array
     */
    protected $data = array();

    /**
     *
     * @var integer
     */
    protected $position = 0;

    /**
     *
     * @param mixed $data
     */
    public function __construct($data = array())
    {
        $this->populate($data);
    }

    /**
     *
     * @param mixed $data
     */
    public function prepend($data)
    {
        $this->data = array_reverse($this->data);
        $this->append($data);
        $this->data = array_reverse($this->data);
    }

    /**
     * Returns key => value array
     *
     * @param string $keyField Field to be key
     * @param string $valueField FIeld to be value
     * @return array $result
     */
    public function getPairs($keyField, $valueField)
    {
        $result = array();

        foreach ($this as $model) {
            $result[$model->{$keyField}] = $model->{$valueField};
        }

        return $result;
    }

    /**
     * @param $size
     * @param bool $preserveKeys
     * @return array
     */
    public function chunk($size, $preserveKeys = false)
    {
        return array_chunk($this->data, $size, $preserveKeys);
    }

    /**
     * Get collection slice
     *
     * @param $limit
     * @param $offset
     * @return App_Model_Collection
     */
    public function getSlice($limit, $offset = 0)
    {
        $data = $this->toArray();

        $data = array_slice($data, $offset, $limit);

        return new $this($data);
    }

    /**
     * @param string|array $key
     * @return array
     */
    public function getGrouppedByKey($keys)
    {
        if (!is_array($keys)) {
            $keys = array($keys);
        }

        if (count($keys) > 0) {
            $key = array_shift($keys);
        } else {
            return $this;
        }

        $result = array();

        foreach ($this as $model) {
            if (!isset($result[$model->$key])) {
                $result[$model->$key] = new static;
            }
            $result[$model->$key]->append($model);
        }

        foreach ($result as &$collection) {
            $collection = $collection->getGrouppedByKey($keys);
        }

        return $result;
    }

    /**
     *
     * @param array $cond
     * @return Collection
     */
    public function collectItemsByCond(array $cond = array())
    {
        $collection = new $this;

        if (count($this)) {
            foreach ($this as $item) {
                $itemMatch = true;
                foreach ($cond as $key => $value) {
                    if (is_array($value)) {
                        if (!in_array($item->$key, $value)) {
                            $itemMatch = false;
                        }
                    } else {
                        if ($item->$key != $value) {
                            $itemMatch = false;
                        }
                    }
                }

                if ($itemMatch) {
                    $collection->append($item);
                }
            }
        }

        return $collection;
    }

    /**
     *
     * @param array $cond
     * @return Collection|null
     */
    public function getItemByCond(array $cond = array())
    {
        foreach ($this as $item) {
            $itemMatch = true;
            foreach ($cond as $key => $value) {
                if ($item->$key != $value) {
                    $itemMatch = false;
                }
            }

            if ($itemMatch) {
                return $item;
            }
        }
    }

    /**
     * @param array $cond
     * @return bool
     */
    public function removeByCond(array $cond = array())
    {
        $success = false;
        foreach ($this as $pos => $item) {
            $itemMatch = true;
            foreach ($cond as $key => $value) {
                if (is_array($value)) {
                    if (!in_array($item->$key, $value)) {
                        $itemMatch = false;
                    }
                } else {
                    if ($item->$key != $value) {
                        $itemMatch = false;
                    }
                }
            }

            if ($itemMatch) {
                unset($this->data[$pos]);
                $success = true;
            }
        }
        $this->data = array_values($this->data);
        $this->rewind();
        return $success;
    }

    /**
     *
     * @param array $cond
     * @return Collection
     */
    public function collectItemsByCallback($callback)
    {
        $collection = new $this;

        if (count($this)) {
            foreach ($this as $item) {
                $itemMatch = $callback($item);

                if ($itemMatch) {
                    $collection->append($item);
                }
            }
        }

        return $collection;
    }

    /**
     *
     * @param mixed $data
     */
    public function populate($data)
    {
        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } else {
                $data = (array)$data;
            }
        }

        if (!is_array($data)) {
            throw new CollectionException("Can't populate data. Must be an array or object.");
        }

        $this->clear();

        foreach ($data as $model) {
            $this[] = $model;
        }
    }

    /**
     *
     * @param string $className
     * @return Collection
     */
    public function setModelClass($className)
    {
        $this->modelClassName = $className;
        return $this;
    }

    /**
     * @param $fieldName
     * @return number
     */
    public function getFieldSum($fieldName)
    {
        return array_sum($this->extractField($fieldName));
    }

    /**
     * Returns array of given model fields
     *
     * @param array $fields Fields to fetch
     * @return array Result
     */
    public function extractFields(array $fields)
    {
        $result = array();

        foreach ($this as $model) {
            $row = array();

            foreach ($fields as $field) {
                $row[$field] = $model->{$field};
            }

            array_push($result, $row);
        }

        return $result;
    }

    /**
     * Returns array of one given model fields
     *
     * @param mixed $field Field name
     * @return array Result
     */
    public function extractField($field, $unique = false)
    {
        $fields = $this->extractFields(array($field));

        $result = array();

        foreach ($fields as $fieldValues) {
            $result[] = $fieldValues[$field];
        }

        if ($unique) {
            $result = array_unique($result);
        }

        return $result;
    }

    /**
     *
     */
    public function delete()
    {
        foreach ($this as $item) {
            /** @var ModelInterface $item */
            $item->delete();
        }
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        if (null === $this->modelClassName) {
            // try to auto detect model class
            $className = get_class($this);
            if (strpos($className, 'Collection')) {
                $this->setModelClass(str_replace('Collection', '', $className));
            }
        }
        return $this->modelClassName;
    }

    /**
     * Shuffles (randomizes) collection
     * @return ModelInterface_Collection
     */
    public function shuffle()
    {
        shuffle($this->data);
        $this->rewind();
        return $this;
    }

    /**
     * Reverse collection
     * @return Collection
     */
    public function reverse()
    {
        $this->data = array_reverse($this->data);
        $this->rewind();
        return $this;
    }

    /**
     *
     * @return ModelInterface
     */
    public function getFirst()
    {
        if (isset($this->data[0])) {
            return $this->data[0];
        }
    }

    /**
     * Get previous item in collection
     * @param ModelInterface $item
     * @return ModelInterface|null
     */
    public function getPrevious($item)
    {
        foreach ($this->data as $key => $value) {
            if ($item === $value) {
                if (isset($this->data[$key - 1])) {
                    return $this->data[$key - 1];
                }
                break;
            }
        }

        return null;
    }

    /**
     * Get next item in collection
     * @param ModelInterface $item
     * @return ModelInterface|null
     */
    public function getNext($item)
    {
        foreach ($this->data as $key => $value) {
            if ($item == $value) {
                if (isset($this->data[$key + 1])) {
                    return $this->data[$key + 1];
                }
                break;
            }
        }

        return null;
    }

    /**
     *
     * @return ModelInterface
     */
    public function getLast()
    {
        return $this->data[count($this->data) - 1];
    }

    /**
     * @param ModelInterface $item
     * @return bool
     */
    public function isLast($item)
    {
        return $item === $this->getLast();
    }

    /**
     * @param ModelInterface $item
     * @return bool
     */
    public function isFirst($item)
    {
        return $item === $this->getFirst();
    }

    /**
     * @return ModelInterface
     */
    public function current()
    {
        if (isset($this->data[$this->position])) {
            return $this->data[$this->position];
        } else {
            return null;
        }
    }

    /**
     *
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @return int|scalar
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->position < $this->count();
    }

    /**
     *
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Methods implements Countable
     */

    public function count()
    {
        return count($this->data);
    }

    /**
     * Methods implements arrayAccess
     */

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed|void
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof ModelInterface) {
            $value = new $this->modelClassName($value);
        }

        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }

        return $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }


    /**
     * @param bool $recursive
     * @return array
     */
    public function toArray($recursive = false)
    {
        if ($recursive) {
            $result = array();
            foreach ($this as $model) {
                /* @var ModelInterface $model */
                $result[] = $model->toArray();
            }
            return $result;
        } else {
            return $this->data;
        }
    }

    /**
     * @param $data
     * @return $this
     */
    public function append($data)
    {
        if ($data instanceof ModelInterface) {
            $this->data[] = $data;
        } elseif ($data instanceof Collection) {
            foreach ($data as $item) {
                $this->data[] = $item;
            }
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function clear()
    {
        $this->data = array();
        $this->position = 0;
        return $this;
    }
}
