<?php

namespace Bluetea\ImportBundle\Factory;

use Bluetea\ImportBundle\Exception\ImportException;
use Bluetea\ImportBundle\Model\ImportInterface;

class ImportFactory
{
    /**
     * Import entity
     *
     * @var ImportInterface
     */
    protected $importEntity;

    /**
     * Contains options (optional)
     *
     * @var array
     */
    protected $options = [];

    /**
     * Default parser
     */
    public function parse()
    {
        if (empty($this->importEntity) || !$this->importEntity instanceof ImportInterface) {
            throw new ImportException("Import entity not found or not an instance of the Import model");
        }

        if (!file_exists($this->importEntity->getFilePath())) {
            throw new ImportException("Couldn't find file " . $this->importEntity->getFilePath());
        }
    }

    /**
     * @param ImportInterface $importEntity
     */
    public function setImportEntity(ImportInterface $importEntity)
    {
        $this->importEntity = $importEntity;
    }

    /**
     * @return ImportInterface
     */
    public function getImportEntity()
    {
        return $this->importEntity;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}