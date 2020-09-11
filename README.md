# DoctrineEntityManagerBundle

Symfony Bundle helping to create and access to the own Doctrine entity manager classes
(with operations doing on the entity).

## Install

```sh
composer require arturdoruch/doctrine-entity-manager-bundle
```

Register bundle in kernel class.

```php
$bundles = [
    new ArturDoruch\DoctrineEntityManagerBundle\ArturDoruchDoctrineEntityManagerBundle(),
];
```

## Usage

### Creating entity manager

In order to creating Doctrine entity manager class extend the
`ArturDoruch\DoctrineEntityManagerBundle\AbstractEntityManager` abstract class,
and implement required `getRepository()` method returning the repository class of managing entity.

Example:

```php
<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Foo;
use ArturDoruch\DoctrineEntityManagerBundle\AbstractEntityManager;

class FooManager extends AbstractEntityManager
{
    public function getRepository()
    {
        return $this->doGetRepository(Foo::class);
    }
    
    // Custom entity manager methods.
    
    /*public function create(): Foo
    {
        $foo = new Foo();
        
        return $foo;
    }
        
    
    public function save(Foo $foo)
    {
        $this->persist($foo);
    }
    
    
    public function remove(Foo $foo)
    {
        $this->doRemove($foo);
    }*/
}
```

### Register service as entity manager

To register service as entity manager, add the `arturdoruch.doctrine_entity_manager` tag
to the service configuration.

```yml
doctrine.foo_manager:
    class: AppBundle\Doctrine\FooManager
    tags: ['arturdoruch.doctrine_entity_manager']
```

### Accessing entity managers

All managers are registered in `ArturDoruch\DoctrineEntityManagerBundle\EntityManagerRegistry` class.
To get specific entity manager pass its class name to the `get()` method.

In controller:

```php
<?php

$emr = $this->get('arturdoruch.doctrine_entity_manager_registry');
$fooManager = $emr->get(FooManager::class);
$barManager = $emr->get(BarManager::class);
```

In entity manager:

```php
<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Foo;
use AppBundle\Doctrine\BarManager;
use ArturDoruch\DoctrineEntityManagerBundle\AbstractEntityManager;

class FooManager extends AbstractEntityManager
{
    /**
     * @var BarManager
     */
    private $barManager;
    
    public function initialize()
    {
        $this->barManager = $this->getManager(BarManager::class);
    }
}
``` 

## Class API

`ArturDoruch\DoctrineEntityManagerBundle\AbstractEntityManager` 
 
Protected methods to use in entity manager class:

  * getEntityManagerName
  * initialize
  * getManager
  * persist
  * doRemove