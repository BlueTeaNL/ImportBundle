<?php

namespace Bluetea\ImportBundle\Import;

use Bluetea\ImportBundle\Exception\ImportException;
use Bluetea\ImportBundle\Import\ImportLogger;
use Bluetea\ImportBundle\Factory\FactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;

abstract class BaseImport implements ImportInterface
{
    /**
     * @var ImportLogger
     */
    protected $logger;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;

    /**
     * Constructor
     *
     * @param $container
     * @param ObjectManager $om
     */
    public function __construct($container, ObjectManager $om)
    {
        $this->container = $container;
        $this->em = $om;
        // APC Cache
        $apcCache = $this->em->getConfiguration()->getResultCacheImpl();
        $apcCache->deleteAll();
    }

    /**
     * Import
     *
     * @return bool
     */
    public function import()
    {
        $this->logger->add('Start import');
        // Parse file with the right parser (factory) and loop through all lines
        foreach ($this->factory->parse() as $key => $line) {
            if(count($line) > 1) {
                try
                {
                    $this->importLine(array_map('trim', $line));
                }
                // Catch all import exceptions
                catch (ImportException $e)
                {
                    $this->logger->add(sprintf('Line %d: %s', $key, $e->getMessage()), ImportLogger::ERROR);
                    $this->logger->countError();
                    if ($this->logger->getErrorCount() > 1000) {
                        $this->logger->add('To much errors... ending import', ImportLogger::CRITICAL);
                        break;
                    }
                }
            }
        }
        $this->logger->add('End import');

        // Save statistics in log
        $this->logger->logStatistics();

        return true;
    }

    /**
     * Delete from cache
     *
     * @param $id
     */
    public function deleteFromCache($id)
    {
        $apcCache = $this->em->getConfiguration()->getResultCacheImpl();
        $apcCache->delete($id);
    }

    /**
     * Persist and flush
     *
     * @param $entity
     * @param null $originalEntity
     * @param null $cacheId
     * @param bool $clearAfterFlush
     */
    public function persistAndFlush($entity, $originalEntity = null, $cacheId = null, $clearAfterFlush = false)
    {
        // Check if entity has changed or not
        if ($originalEntity != $entity) {
            // If the entity is updated, remove the query result for this entity from the cache
            // so the next query doesn't contain old results
            if ($cacheId) {
                $this->deleteFromCache($cacheId);
            }
            $validator = $this->container->get('validator');
            // Check if the entity is valid
            $errors = $validator->validate($entity);

            if (count($errors) > 0) {
                $this->logger->add(sprintf('Entity isn\'t valid'), ImportLogger::ERROR);
                foreach ($errors as $error) {
                    $this->logger->add(sprintf('%s (%s: %s)', $error->getMessage(), $error->getPropertyPath(), $error->getInvalidValue()), ImportLogger::ERROR);
                }
                $this->logger->countError();
            } else {
                // Try to persist and flush the entity
                try {
                    if ($entity->getId() !== null) {
                        $this->logger->countUpdated();
                    } else {
                        $this->logger->countAdded();
                    }
                    $this->em->persist($entity);
                    $this->em->flush();

                    if ($clearAfterFlush) {
                        $this->em->clear();
                    }
                } catch (\Doctrine\ORM\ORMException $e) {
                    $this->logger->add($e->getMessage(), ImportLogger::CRITICAL);
                }
            }
        } else {
            $this->logger->countUnchanged();
        }
    }

    /**
     * @param $entity
     * @param null $cacheId
     *
     * Delete given entity
     */
    public function deleteEntity($entity, $cacheId = null)
    {
        // remove the entity result from the cache, so the next query doesn't contain old results
        if ($cacheId) {
            $this->deleteFromCache($cacheId);
        }

        $validator = $this->container->get('validator');
        // Check if the entity is valid
        $errors = $validator->validate($entity);
        if (count($errors) > 0) {
            $this->logger->add(sprintf('Entity isn\'t valid'), ImportLogger::ERROR);
            foreach ($errors as $errorI => $error) {
                $this->logger->add(sprintf('Error %s: %s', $errorI + 1, $error->getMessage()), ImportLogger::ERROR);
            }
            $this->logger->countError();
        } else {
            // Try to persist and flush the entity
            try {
                $this->logger->countDeleted();
                $this->em->remove($entity);
                $this->em->flush();
            } catch (\Doctrine\ORM\ORMException $e) {
                $this->logger->add($e->getMessage(), ImportLogger::CRITICAL);
            }
        }
    }

    /**
     * Get the Import Logger
     *
     * @return ImportLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set Import Logger
     *
     * @param \Bluetea\ImportBundle\Import\ImportLogger $logger
     */
    public function setLogger(ImportLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get the Import Factory
     *
     * @return FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Set factory
     *
     * @param FactoryInterface $factory
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }
}