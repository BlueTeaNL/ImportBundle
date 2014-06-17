<?php

namespace Bluetea\ImportBundle\Services;

use Bluetea\ImportBundle\BlueteaImportEvents;
use Bluetea\ImportBundle\Event\GetImportEvent;
use Bluetea\ImportBundle\Exception\ImportException;
use Bluetea\ImportBundle\Factory\FactoryInterface;
use Bluetea\ImportBundle\Import\ImportInterface;
use Bluetea\ImportBundle\Import\ImportLogger;
use Bluetea\ImportBundle\Model\ImportLogManagerInterface;
use Bluetea\ImportBundle\Model\ImportManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Import
{
    /**
     * @var \Bluetea\ImportBundle\Import\ImportInterface
     */
    protected $importType;

    /**
     * @var \Bluetea\ImportBundle\Factory\FactoryInterface
     */
    protected $factory;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var \Bluetea\ImportBundle\Model\ImportManagerInterface
     */
    protected $importManager;

    /**
     * @var \Bluetea\ImportBundle\Model\ImportLogManagerInterface
     */
    protected $importLogManager;

    /**
     * @var \Bluetea\ImportBundle\Import\ImportLogger
     */
    protected $importLogger = null;

    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher,
        ImportManagerInterface $importManager,
        ImportLogManagerInterface $importLogManager
    )
    {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->importManager = $importManager;
        $this->importLogManager = $importLogManager;
    }

    public function startImport()
    {
        $this->importLogger = new ImportLogger();

        $this->validateMandatoryProperties();

        $this->importType->setFactory($this->factory);
        $this->importType->setLogger($this->importLogger);

        $event = new GetImportEvent($this->importType);
        $this->eventDispatcher->dispatch(BlueteaImportEvents::RUN_IMPORT_INITIALIZE, $event);

        // Try to import
        try {
            $this->importType->import();
        } catch (\Exception $exception) {
            $this->importLogger->add($exception->getMessage(), ImportLogger::CRITICAL);
            $this->importLogger->countError();
        }

        // Save statistics in log
        $this->importLogger->logStatistics();

        // Add ImportLog
        $importLog = $this->importLogManager->createImportLog();
        $importLog->setImport($this->factory->getImportEntity());
        $importLog->setDatetime(new \DateTime());
        $importLog->setLog($this->importLogger->getLog());
        $this->importLogManager->updateImportLog($importLog);

        $importEntity = $this->factory->getImportEntity();
        $statistics = $this->getStatistics();
        if ($statistics['error'] > 0) {
            $importEntity->setStatus(\Bluetea\ImportBundle\Entity\Import::ERROR);
        } elseif ($statistics['skipped'] > 0) {
            $importEntity->setStatus(\Bluetea\ImportBundle\Entity\Import::WARNING);
        } else {
            $importEntity->setStatus(\Bluetea\ImportBundle\Entity\Import::READY);
        }
        $this->importManager->updateImport($importEntity);

        return true;
    }

    protected function validateMandatoryProperties()
    {
        $importEntity = $this->factory->getImportEntity();
        $importType = $this->importType;

        // Check if file path and import type are set
        if (empty($importEntity) || empty($importType)) {
            throw new ImportException("Import entity or import type aren't set");
        }

        // Check if module implements the ImportInterface
        if (!$this->importType instanceof ImportInterface) {
            throw new ImportException("Import type does not implement the ImportInterface class");
        }
    }

    /**
     * Set options
     *
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->factory->setOptions($options);
    }

    /**
     * Set the import entity
     *
     * @param \Bluetea\ImportBundle\Model\Import $importEntity
     */
    public function setImportEntity(\Bluetea\ImportBundle\Model\Import $importEntity)
    {
        $this->factory->setImportEntity($importEntity);
    }

    /**
     * @param \Bluetea\ImportBundle\Import\ImportInterface $importType
     */
    public function setImportType($importType)
    {
        $this->importType = $importType;
    }

    /**
     * Return the statistics from the logger if initialized
     * The logger is initialized when you start the import
     *
     * @return array|null
     */
    public function getStatistics()
    {
        if (!is_null($this->importLogger)) {
            return $this->importLogger->getStatistics();
        } else {
            return null;
        }
    }
}