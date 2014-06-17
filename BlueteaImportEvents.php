<?php

namespace Bluetea\ImportBundle;

/**
 * Contains all events thrown in the BlueteaImportBundle
 */
final class BlueteaImportEvents
{
    /**
     * The IMPORT_INITIALIZE event occurs when the run import process is initialized
     *
     * This event allows you to get the import entity before running the import
     * The event listener method receives a Bluetea\ImportBundle\Events\GetImportEvent instance
     */
    const IMPORT_INITIALIZE = 'bluetea_import.import_initialize';

    /**
     * The IMPORT_SUCCESS event occurs when the run import process is finished
     *
     * This event allow you to modify the import and import log entity before flushing
     * The event listener method receives a Bluetea\ImportBundle\Events\GetImportLogEvent instance
     */
    const IMPORT_SUCCESS = 'bluetea_import.import_success';

    /**
     * The IMPORT_COMPLETED event occurs when the run import process is finished
     *
     * This event allow you to modify the import and import log entity before flushing
     * The event listener method receives a Bluetea\ImportBundle\Events\GetImportLogEvent instance
     */
    const IMPORT_COMPLETED = 'bluetea_import.import_completed';
}