<?php

namespace Bluetea\ImportBundle\Exception;

/**
 * Thrown when an argument isn't valid
 */
class InvalidLineCountException extends ImportException
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('Line count does not match the import specification');
    }
}
