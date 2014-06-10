<?php

namespace Bluetea\ImportBundle\Services;

use Bluetea\ImportBundle\Import\ImportInterface;

class ImportChain
{
    /**
     * Contains all import types
     *
     * @var array
     */
    private $importTypes;

    /**
     * Construct the ImportChain class an initialize the importTypes property as an array
     */
    public function __construct()
    {
        $this->importTypes = array();
    }

    /**
     * Add an import type which implements the ImportInterface interface
     *
     * @param ImportInterface $importType
     * @param string $name
     */
    public function addImportType(ImportInterface $importType, $name)
    {
        $this->importTypes[$name] = $importType;
    }

    /**
     * Returns all import types
     *
     * @return ImportInterface[]
     */
    public function getImportTypes()
    {
        return $this->importTypes;
    }

    /**
     * Get an import type by name
     *
     * @param string $importType
     * @return ImportInterface
     */
    public function getImportType($importType)
    {
        if (array_key_exists($importType, $this->importTypes))
            return $this->importTypes[$importType];
    }

    /**
     * Order the import types on name
     */
    public function sortImportChain()
    {
        // Sort keys
        ksort($this->importTypes);
        // Create empty array
        $array = array();
        // Loop through importTypes
        foreach ($this->importTypes as $name => $importType) {
            $array[$name] = $importType;
        }
        // Set importTypes property with consecutive keys
        $this->importTypes = $array;
    }
}