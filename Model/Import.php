<?php

namespace Bluetea\ImportBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\Collection;

abstract class Import implements ImportInterface
{
    const PENDING = "Pending";
    const QUEUE = "Queue";
    const RUNNING = "Running";
    const READY = "Ready";
    const ERROR = "Error";
    const WARNING = "Warning";

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var \Bluetea\ImportBundle\Import\ImportInterface
     */
    protected $importType;

    /**
     * @var \DateTime
     */
    protected $datetime;

    /**
     * @var string
     */
    protected $status = self::PENDING;

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var Collection
     */
    protected $logs;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param \Bluetea\ImportBundle\Import\ImportInterface $importType
     */
    public function setImportType(\Bluetea\ImportBundle\Import\ImportInterface $importType)
    {
        $this->importType = $importType;
    }

    /**
     * @return \Bluetea\ImportBundle\Import\ImportInterface
     */
    public function getImportType()
    {
        return $this->importType;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param ImportLog[] $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    /**
     * @param ImportLog $importLog
     */
    public function addLog(ImportLog $importLog)
    {
        if (!$this->logs->contains($importLog)) {
            $this->logs->add($importLog);
        }
    }

    /**
     * @param ImportLog $importLog
     */
    public function removeLog(ImportLog $importLog)
    {
        $this->logs->removeElement($importLog);
    }

    /**
     * @return ImportLog[]
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }
}