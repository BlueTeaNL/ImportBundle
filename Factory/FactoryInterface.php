<?php

namespace Bluetea\ImportBundle\Factory;

interface FactoryInterface
{
    /**
     * Parse the file and return a multi dimensional array
     * Example: array[0] => array which is a line with columns or fields
     *
     * @return array
     */
    public function parse();

    /**
     * Set the import entity
     *
     * @param \Bluetea\ImportBundle\Model\Import $importEntity
     */
    public function setImportEntity($importEntity);

    /**
     * Returns the import entity
     *
     * @return mixed
     */
    public function getImportEntity();

    /**
     * Set options
     *
     * @var array $options
     */
    public function setOptions($options);

    /**
     * Returns the options
     *
     * @return array
     */
    public function getOptions();
}