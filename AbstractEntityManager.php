<?php

namespace ArturDoruch\DoctrineEntityManagerBundle;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
abstract class AbstractEntityManager
{
    /**
     * @var EntityManager Doctrine entity manager.
     */
    protected $em;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManagerRegistry
     */
    private $registry;

    final public function construct(EntityManagerRegistry $registry)
    {
        if ($this->registry) {
            return;
        }

        $this->registry = $registry;
        // Set entity manager and connection.
        $this->em = $registry->getDoctrine()->getManager($this->getEntityManagerName());
        $this->connection = $this->em->getConnection();

        $this->initialize();
    }

    /**
     * Gets Doctrine entity manager name (configured in "doctrine.orm.entity_managers")
     * for entity managed by this manager.
     *
     * @return null|string
     */
    protected function getEntityManagerName(): ?string
    {
        return null;
    }

    /**
     * Makes manager initializations. E.g. get another manager $this->getManager(ClassNameManager::class);.
     */
    protected function initialize()
    {
    }

    /**
     * Gets cached repository for entity managing by this manager.
     * This method should call "doGetRepository()" to get repository.
     *
     * @return EntityRepository
     */
    abstract public function getRepository();

    /**
     * Gets cached repository for entity managing by this manager.
     *
     * @param string $className
     *
     * @return EntityRepository
     */
    protected function doGetRepository(string $className)
    {
        if ($this->repository === null) {
            $this->repository = $this->em->getRepository($className);
        }

        return $this->repository;
    }

    /**
     * @param string $class
     *
     * @return AbstractEntityManager
     */
    protected function getManager(string $class): AbstractEntityManager
    {
        return $this->registry->get($class);
    }

    /**
     * @param object $entity The entity object.
     * @param bool $flush
     */
    protected function persist($entity, $flush = true)
    {
        $this->em->persist($entity);

        if ($flush === true) {
            $this->em->flush();
        }
    }

    /**
     * @param object $entity The entity object.
     * @param bool $flush
     */
    protected function doRemove($entity, $flush = true)
    {
        $this->em->remove($entity);

        if ($flush === true) {
            $this->em->flush();
        }
    }
}
