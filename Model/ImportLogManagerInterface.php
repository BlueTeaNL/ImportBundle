<?php

namespace Bluetea\ImportBundle\Model;

interface ImportLogManagerInterface
{
    /**
     * Creates an empty ImportLog instance
     *
     * @return ImportLogInterface
     */
    public function createImportLog();

    /**
     * Deletes an ImportLog
     *
     * @param ImportLogInterface $ImportLog
     */
    public function deleteImportLog(ImportLogInterface $ImportLog);

    /**
     * Find a user by the given criteria
     *
     * @param array $criteria
     * @return ImportLogInterface
     */
    public function findImportLogBy(array $criteria);

    /**
     * Returns a collection with all ImportLogs
     *
     * @return ImportLogInterface[]
     */
    public function findImportLogs();

    /**
     * Returns the ImportLog's fully qualified class name
     *
     * @return string
     */
    public function getClass();

    /**
     * Updates an ImportLog
     *
     * @param ImportLogInterface $ImportLog
     * @return ImportLogInterface
     */
    public function updateImportLog(ImportLogInterface $ImportLog);
}