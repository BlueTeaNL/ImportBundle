<?php

namespace Bluetea\ImportBundle;

/**
 * Contains all events thrown in the BlueteaImportBundle
 */
final class BlueteaImportEvents
{
    /**
     * The RUN_IMPORT_INITIALIZE event occurs when the run import process is initialized
     *
     * This event allows you to get the import entity before running the import
     * The event listener method receives a Bluetea\ImportBundle\Events\GetImportEvent instance
     */
    const RUN_IMPORT_INITIALIZE = 'bluetea_import.run_import.initialize';

    /**
     * The RUN_IMPORT_SUCCESS event occurs when the run import process is finished
     *
     * This event allow you to modify the import and import log entity before flushing
     * The event listener method receives a Bluetea\ImportBundle\Events\GetImportLogEvent instance
     */
    const RUN_IMPORT_COMPLETED = 'bluetea_import.run_import.completed';
}