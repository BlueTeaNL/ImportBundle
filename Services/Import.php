<?php

namespace Bluetea\ImportBundle\Services;

use Bluetea\ImportBundle\BlueteaImportEvents;
use Bluetea\ImportBundle\Event\GetStatusEvent;
use Bluetea\ImportBundle\Event\ImportEvent;
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

    /**
     * Constructor
     *
     * @param FactoryInterface $factory
     * @param EventDispatcherInterface $eventDispatcher
     * @param ImportManagerInterface $importManager
     * @param ImportLogManagerInterface $importLogManager
     */
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

    /**
     * Start the import process
     *
     * @return bool
     */
    public function startImport()
    {
        // Validate the given Import entity and import type
        $this->validateMandatoryProperties();

        // Initialize a new ImportLogger instance
        $this->importLogger = new ImportLogger();

        // Set the given factory and logger
        $this->importType->setFactory($this->factory);
        $this->importType->setLogger($this->importLogger);

        // Dispatch IMPORT_INITIALIZE event
        $event = new ImportEvent($this->importType);
        $this->eventDispatcher->dispatch(BlueteaImportEvents::IMPORT_INITIALIZE, $event);

        // Try to import
        try {
            $this->importType->import();
        } catch (\Exception $exception) {
            $this->importLogger->add($exception->getMessage(), ImportLogger::CRITICAL);
            $this->importLogger->countError();
        }

        // Add ImportLog
        $this->addImportLog();

        // Dispatch IMPORT_SUCCESS event
        $event = new GetStatusEvent($this->importType);
        $this->eventDispatcher->dispatch(BlueteaImportEvents::IMPORT_SUCCESS, $event);

        // Get import entity from factory
        $importEntity = $this->factory->getImportEntity();
        // Set the progress to 100 %
        $importEntity->setProgress(100);
        // Handle the status of the import
        if (!is_null($event->getStatus())) {
            $importEntity->setStatus($event->getStatus());
        } else {
            $importEntity->setStatus(\Bluetea\ImportBundle\Model\Import::READY);
        }
        // Update the import entity
        $this->importManager->updateImport($importEntity);

        // Dispatch IMPORT_COMPLETED event
        $event = new ImportEvent($this->importType);
        $this->eventDispatcher->dispatch(BlueteaImportEvents::IMPORT_COMPLETED, $event);

        return true;
    }

    /**
     * Add the import log
     */
    protected function addImportLog()
    {
        $importLog = $this->importLogManager->createImportLog();
        $importLog->setImport($this->factory->getImportEntity());
        $importLog->setDatetime(new \DateTime());
        $importLog->setLog($this->importLogger->getLog());
        $importLog->setStatistics($this->importLogger->getStatistics());
        $this->importLogManager->updateImportLog($importLog);
    }

    /**
     * Validate the mandatory properties before starting the import
     *
     * @throws \Bluetea\ImportBundle\Exception\ImportException
     */
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