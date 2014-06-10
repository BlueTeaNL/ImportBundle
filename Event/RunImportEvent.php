<?php

namespace Bluetea\ImportBundle\Event;

class RunImportEvent
{
    protected $importEntity;
    protected $importLog;

    function __construct($importEntity, $importLog)
    {
        $this->importEntity = $importEntity;
        $this->importLog = $importLog;
    }

    /**
     * @return mixed
     */
    public function getImportEntity()
    {
        return $this->importEntity;
    }

    /**
     * @return mixed
     */
    public function getImportLog()
    {
        return $this->importLog;
    }
}