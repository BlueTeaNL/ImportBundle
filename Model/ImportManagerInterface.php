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
     * Find an import by id
     *
     * @param int $id
     * @return ImportInterface
     */
    public function findImport($id);

    /**
     * Find a user by the given criteria
     *
     * @param array $criteria
     * @return ImportInterface
     */
    public function findImportBy(array $criteria);

    /**
     * Find imports by the given criteria
     *
     * @param array $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     * @return ImportInterface
     */
    public function findImportsBy(array $criteria, $orderBy = null, $limit = null, $offset = null);

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