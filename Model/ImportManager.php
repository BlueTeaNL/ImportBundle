<?php

namespace Bluetea\ImportBundle\Model;

abstract class ImportManager implements ImportManagerInterface
{
    /**
     * Creates an empty import instance
     *
     * @return ImportInterface
     */
    public function createImport()
    {
        $class = $this->getClass();
        $import = new $class;

        return $import;
    }
}