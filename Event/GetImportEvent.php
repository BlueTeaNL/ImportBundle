<?php

namespace Bluetea\ImportBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class GetImportEvent extends Event
{
    protected $importEntity;

    function __construct($importEntity)
    {
        $this->importEntity = $importEntity;
    }

    /**
     * @return mixed
     */
    public function getImportEntity()
    {
        return $this->importEntity;
    }
}