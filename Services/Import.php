<?php

namespace Bluetea\ImportBundle\Services;

use Bluetea\ImportBundle\Exception\ImportException;
use Bluetea\ImportBundle\Factory\FactoryInterface;
use Bluetea\ImportBundle\Import\ImportInterface;
use Bluetea\ImportBundle\Import\ImportLogger;
use Bluetea\ImportBundle\Model\ImportLogManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var \Bluetea\ImportBundle\Model\ImportLogManagerInterface
     */
    protected $importLogManager;

    /**
     * @var \Bluetea\ImportBundle\Import\ImportLogger
     */
    protected $importLogger;

    public function __construct(
        FactoryInterface $factory,
        EventDispatcher $eventDispatcher,
        ImportLogManagerInterface $importLogManager
    )
    {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->importLogManager = $importLogManager;

        $this->importLogger = new ImportLogger();
    }

    public function startImport()
    {
        $this->validateMandatoryProperties();

        $this->importType->setFactory($this->factory);
        $this->importType->setLogger($this->importLogger);

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
}