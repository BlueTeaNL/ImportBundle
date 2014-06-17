<?php

namespace Bluetea\ImportBundle\Event;

class GetImportLogEvent extends GetImportEvent
{
    protected $importLogEntity;

    function __construct($importEntity, $importLogEntity)
    {
        parent::__construct($importEntity);
        $this->importLogEntity = $importLogEntity;
    }

    /**
     * @return mixed
     */
    public function getImportLogEntity()
    {
        return $this->importLogEntity;
    }
}