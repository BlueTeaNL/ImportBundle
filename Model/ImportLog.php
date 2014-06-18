<?php

namespace Bluetea\ImportBundle\Model;

abstract class ImportLog implements ImportLogInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $log;

    /**
     * @var \DateTime
     */
    protected $datetime;

    /**
     * @var ImportInterface
     */
    protected $import;

    /**
     * @var array
     */
    protected $statistics;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param ImportInterface $import
     */
    public function setImport(ImportInterface $import)
    {
        $this->import = $import;
    }

    /**
     * @return ImportInterface
     */
    public function getImport()
    {
        return $this->import;
    }

    /**
     * @param mixed $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * @return mixed
     */
    public function getStatistics()
    {
        return $this->statistics;
    }
}