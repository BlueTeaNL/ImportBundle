<?php

namespace Bluetea\ImportBundle\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class ImportHandler extends AbstractProcessingHandler
{
    function __construct($level = Logger::DEBUG, $bubble = true)
    {

    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        // TODO: Implement write() method.
    }
}