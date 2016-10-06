Simpleorm
=========
Super simple ORM on top of PDO. Based on the data mapper pattern.

### Quickstart

##### The model

```php

use Simpleorm\Mapper\Pdo\PdoMapper;

/**
 * @property int    $id
 * @property string $name {"default": "John"}
 *
 * @method static PdoMapper getMapper() {"table": "test"}
 */
class User extends AbstractModel
{
    
}
```

Fields are defined as doc properties, because this is 
* convenient
* easily recognizeable by the IDE
* you dont have the unnecessary boilerplate code with getters and setters

##### The mapper

The mapper once again is defined in the doc comments section. Here we specify, that our user will be handled with the PdoMysqlMapper

```php
@method static PdoMapper getMapper() {"table": "test"}
```

To get the mapper, simply use

```php
User::getMapper();
```

We use a mix of active record and data mapper aproach to managing your entities, therefore you can use it like this:

```php
$user = User::getMapper()->fetch(1);
$user->name = 'NewName';
$user->save();
```

If you want your custom logic and methods, just extend the desired mapper and you are good to go:

```php
class UserMapper extends PdoMysqlMapper 
{
    public function fetchComplicatedReport($someParams)
    {
        $stmt = $this->getConnection()->prepare('...');
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
```
Use this mapper in your model

```php
/**
 * @property int    $id
 *
 * @method static UserMapper getMapper() {"table": "test"}
 */
class User extends AbstractModel
{
}
```
And in your service/controller:

```php
$report = User::getMapper()->fetchComplicatedReport();
```

##### The collection

By default the fetchAll mapper method will return a collection. It is more convenient to work with, than just plain array, and has a lot of usefull methods.

```php
$users = User::getMapper()->fetchAll(['country' => 'Cyprus']);
```

For more details, check the code

##### What else?

###### Writing own mappers

You can write your own mapper for any storage you want. Just implement the MapperInterface

###### MysqlPdo mapper connection settings

```php
PdoMysqlMapper::setConnectionConfig(array(
    'user' => 'user',
    'password' => 'password',
    'host' => '127.0.0.1',
    'port' => '3306',
    'db'   => 'simpleorm_test'
));
```

###### Getters and setters

If you need setters and getters - you can define them explicitly, they will be used before the magic __get __set.

###### Mapper decorators

You can add decorators to mappers, for caching purposes, for example

###### Model state

Models can be clean (unchanged, when fetched from the storage) and dirty (something is changed). You can always access the models
cleanData and data, to see what changed.

##### Final word

As you see, it's super simple, yet effective. If you need an ORM, but dont want to mess with giants like Doctrine, i hope Simpleorm will server you for good.
