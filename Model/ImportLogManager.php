<?php

namespace Bluetea\ImportBundle\Model;

abstract class ImportLogManager implements ImportLogManagerInterface
{
    /**
     * Creates an empty ImportLog instance
     *
     * @return ImportLogInterface
     */
    public function createImportLog()
    {
        $class = $this->getClass();
        $ImportLog = new $class;

        return $ImportLog;
    }
}