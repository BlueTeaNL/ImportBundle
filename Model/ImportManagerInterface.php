<?php

namespace Bluetea\ImportBundle\Model;

interface ImportManagerInterface
{
    /**
     * Creates an empty import instance
     *
     * @return ImportInterface
     */
    public function createImport();

    /**
     * Deletes an import
     *
     * @param ImportInterface $import
     */
    public function deleteImport(ImportInterface $import);

    /**
     * Find a user by the given criteria
     *
     * @param array $criteria
     * @return ImportInterface
     */
    public function findImportBy(array $criteria);

    /**
     * Returns a collection with all imports
     *
     * @return ImportInterface[]
     */
    public function findImports();

    /**
     * Returns the import's fully qualified class name
     *
     * @return string
     */
    public function getClass();

    /**
     * Updates an import
     *
     * @param ImportInterface $import
     * @return ImportInterface
     */
    public function updateImport(ImportInterface $import);
}