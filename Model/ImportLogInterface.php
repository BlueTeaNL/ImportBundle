<?php

namespace Bluetea\ImportBundle\Model;

interface ImportLogInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @param string $log
     */
    public function setLog($log);

    /**
     * @return string
     */
    public function getLog();

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime);

    /**
     * @return mixed
     */
    public function getDatetime();

    /**
     * @param Import $import
     */
    public function setImport(Import $import);

    /**
     * @return Import
     */
    public function getImport();
}