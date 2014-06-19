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
     * @var \Bluetea\ImportBundle\Model\ImportManagerInterface
     */
    protected $importManager;

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

        // Clear the Doctrine cache before importing the file
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
        $this->logger->add($this->container->get('translator')->trans('bluetea_import.start_import', array(), 'import'));
        // Parse the file
        $parsedFile = $this->factory->parse();
        // Get the length of the file
        $fileCount = $this->factory->getLength();
        // Parse file with the right parser (factory) and loop through all lines
        foreach ($parsedFile as $key => $line) {
            // Check for empty lines, we discard them
            if(!(count($line) == 1 && empty($line[0]))) {
                try
                {
                    $this->importLine(array_map('trim', $line));
                }
                // Catch all import exceptions
                catch (ImportException $e)
                {
                    $this->logger->add(sprintf('Line %d: %s', $key, $e->getMessage()), ImportLogger::ERROR);
                    $this->logger->countError();
                    if (
                        $this->logger->getStatistics('error', false) > $this->container->getParameter('bluetea_import.import.error_threshold')
                        &&
                        $this->logger->getStatistics('error', false) == $this->logger->getStatistics('total', false)
                    ) {
                        $this->logger->add(
                            $this->container->get('translator')->trans('bluetea_import.to_much_errors', array(), 'import'),
                            ImportLogger::CRITICAL
                        );
                        // Break the foreach loop and end the import
                        break;
                    }
                }
            }
            if ($key > 0 && $key % $this->container->getParameter('bluetea_import.import.progress_update') === 0) {
                $this->updateProgress(($key / $fileCount) * 100);
            }
        }
        $this->logger->add($this->container->get('translator')->trans('bluetea_import.end_import', array(), 'import'));

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
            if (!is_null($cacheId)) {
                $this->deleteFromCache($cacheId);
            }

            if ($this->validateEntity($entity)) {
                // Check if the entity has an id
                // If it doesn't have an id, we add a new entity and it should be counted
                if ($entity->getId() !== null) {
                    $this->logger->countUpdated();
                } else {
                    $this->logger->countAdded();
                }
                $this->em->persist($entity);

                if ($this->flushEntityManager() && $clearAfterFlush) {
                    $this->em->clear();
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

        if ($this->validateEntity($entity)) {
            $this->logger->countDeleted();
            $this->em->remove($entity);
            $this->flushEntityManager();
        }
    }

    /**
     * Update progress
     *
     * @param $percentage
     */
    protected function updateProgress($percentage)
    {
        $importEntity = $this->factory->getImportEntity();
        $importEntity->setProgress($percentage);
        $this->container->get('bluetea_import.import_manager')->updateImport($importEntity);
    }

    /**
     * Flush the entity manager and catch errors if they occur
     *
     * @return bool
     */
    protected function flushEntityManager()
    {
        try {
            $this->em->flush();
        } catch (\Doctrine\ORM\ORMException $e) {
            $this->logger->add(
                $this->container->get('translator')->trans('bluetea_import.em_error', array(), 'import'),
                ImportLogger::CRITICAL
            );
            $this->logger->add($e->getMessage(), ImportLogger::CRITICAL);

            return false;
        }

        return true;
    }

    /**
     * Validate the entity with the Symfony2 validator. If the entity isn't valid we return false, else we return
     * true.
     *
     * @param $entity
     * @return bool
     */
    protected function validateEntity($entity)
    {
        $errors = $this->container->get('validator')->validate($entity);

        if (count($errors) > 0) {
            $this->logger->add(
                $this->container->get('translator')->trans('bluetea_import.entity_invalid', array(), 'import'),
                ImportLogger::ERROR
            );
            foreach ($errors as $error) {
                $this->logger->add(
                    sprintf('%s (%s: %s)', $error->getMessage(), $error->getPropertyPath(), $error->getInvalidValue()),
                    ImportLogger::ERROR
                );
            }
            $this->logger->countError();

            return false;
        }

        return true;
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

    /**
     * @param \Bluetea\ImportBundle\Model\ImportManagerInterface $importManager
     */
    public function setImportManager($importManager)
    {
        $this->importManager = $importManager;
    }

    /**
     * @return \Bluetea\ImportBundle\Model\ImportManagerInterface
     */
    public function getImportManager()
    {
        return $this->importManager;
    }
}