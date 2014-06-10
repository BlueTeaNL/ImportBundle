<?php

namespace Bluetea\ImportBundle\Import\Type;

use Bluetea\ImportBundle\Exception\ResourceNotFoundException;
use Bluetea\ImportBundle\Import\BaseImport;
use Bluetea\ImportBundle\Import\ImportInterface;
use Bluetea\ImportBundle\Exception\InvalidLineCountException;
use Bluetea\ImportBundle\Exception\InvalidArgumentException;
use Bluetea\ImportBundle\Exception\ImportException;

class ExampleImportType extends BaseImport implements ImportInterface
{
    /**
     * Amount of columns according to the import specification
     *
     * @var int
     */
    private $columnNumbers = 1;

    /**
     * @var bool
     */
    private $createNonExistingEntities = true;

    /**
     * Import
     *
     * @param array $line
     * @throws \Bluetea\ImportBundle\Exception\InvalidLineCountException
     */
    public function importLine($line)
    {
        if (count($line) == $this->columnNumbers) {
            $this->validateImportLine($line);
        } else {
            // If count is invalid, throw the InvalidLineCountException
            throw new InvalidLineCountException();
        }

        /**
        Put array fields in variables
        Description fields are not imported.
        This information is available within the database due table relations
         */
        list ($name) = $line;

        // Finalize the import
        $this->finalizeImport(
            $name
        );
    }

    /**
     * @param $name
     * @throws \Bluetea\ImportBundle\Exception\ImportException
     */
    protected function finalizeImport(
        $name
    )
    {
        // Check if the appointment exists, if not create a new appointment if allowed
        $entity = null;
        // Create a copy for later comparison
        $originalEntity = null;

        echo "Hello $name!";

        // persistAndFlush does validation, checks if an entity is changed and handles the cache
        try {
            $this->persistAndFlush($entity, $originalEntity, null, true);
        } catch (\Exception $e) {
            throw new ImportException('Couldn\'t flush the entities: ' . $e->getMessage());
        }
    }

    protected function validateImportLine($line)
    {
        // Validate name
        if (!is_string($argument = $line[0])) {
            throw new InvalidArgumentException($argument, 'name', 'string');
        }
    }
}