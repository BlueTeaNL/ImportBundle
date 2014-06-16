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
     * Find an import log by id
     *
     * @param int $id
     * @return ImportLogInterface
     */
    public function findImportLog($id);

    /**
     * Find an import log by the given criteria
     *
     * @param array $criteria
     * @return ImportLogInterface
     */
    public function findImportLogBy(array $criteria);

    /**
     * Find import logs by the given criteria
     *
     * @param array $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     * @return ImportLogInterface
     */
    public function findImportLogsBy(array $criteria, $orderBy = null, $limit = null, $offset = null);

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