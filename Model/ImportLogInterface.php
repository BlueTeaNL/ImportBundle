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
     * @param ImportInterface $import
     */
    public function setImport(ImportInterface $import);

    /**
     * @return ImportInterface
     */
    public function getImport();
}