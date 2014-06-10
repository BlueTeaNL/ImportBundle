<?php

namespace Bluetea\ImportBundle\Model;

abstract class ImportLog implements ImportLogInterface
{
    protected $id;
    protected $log;
    protected $datetime;
    protected $import;

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
     * @param Import $import
     */
    public function setImport(Import $import)
    {
        $this->import = $import;
    }

    /**
     * @return Import
     */
    public function getImport()
    {
        return $this->import;
    }
}