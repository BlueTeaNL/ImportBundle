<?php

namespace Bluetea\ImportBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImportInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath);

    /**
     * @return string
     */
    public function getFilePath();

    /**
     * @param string $importType
     */
    public function setImportType($importType);

    /**
     * @return string
     */
    public function getImportType();

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime($datetime);

    /**
     * @return \DateTime
     */
    public function getDatetime();

    /**
     * @param string $status
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file);

    /**
     * @return UploadedFile
     */
    public function getFile();

    /**
     * @param ImportLog[] $logs
     */
    public function setLogs($logs);
    /**
     * @param ImportLog $importLog
     */
    public function addLog(ImportLog $importLog);

    /**
     * @param ImportLog $importLog
     */
    public function removeLog(ImportLog $importLog);

    /**
     * @return ImportLog[]
     */
    public function getLogs();
}