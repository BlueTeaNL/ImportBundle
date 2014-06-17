<?php

namespace Bluetea\ImportBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ImportEvent extends Event
{
    protected $importType;

    function __construct($importType)
    {
        $this->importType = $importType;
    }

    /**
     * @return mixed
     */
    public function getImportType()
    {
        return $this->importType;
    }
}