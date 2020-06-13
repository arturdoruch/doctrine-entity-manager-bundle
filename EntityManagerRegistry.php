<?php

namespace ArturDoruch\DoctrineEntityManagerBundle;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class EntityManagerRegistry
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * @var AbstractEntityManager[]
     */
    private $managers = [];

    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    public function getDoctrine(): Doctrine
    {
        return $this->doctrine;
    }


    public function register(AbstractEntityManager $manager)
    {
        $this->managers[get_class($manager)] = $manager;
        $manager->setRegistry($this);
    }

    /**
     * Gets entity manager.
     *
     * @param string $class The fully qualified entity manager class name.
     *                      The entity manager must extends ArturDoruch\ScraperBundle\Doctrine\AbstractEntityManager class.
     *
     * @return AbstractEntityManager
     *
     * @throws \InvalidArgumentException when requested entity manager does not exist.
     */
    public function get(string $class): AbstractEntityManager
    {
        if (!isset($this->managers[$class])) {
            $this->validateClass($class);
            $this->register(new $class());
        }

        return $this->managers[$class];
    }


    private function validateClass(string $class)
    {
        $parentClass = new \ReflectionClass($class);

        while ($parentClass = $parentClass->getParentClass()) {
            if ($parentClass->getName() === AbstractEntityManager::class) {
                return;
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'The requested entity manager class "%s" must extend the "%s" class.', $class, AbstractEntityManager::class
        ));
    }
}
 