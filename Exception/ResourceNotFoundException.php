<?php

namespace Bluetea\ImportBundle\Exception;

/**
 * Thrown when a resource isn't found
 */
class ResourceNotFoundException extends ImportException
{
    /**
     * Constructor.
     *
     * @param mixed $resource The resource
     * @param null|string $reference
     */
    public function __construct($resource, $reference = null)
    {
        if (is_object($resource)) {
            $resource = get_class($resource);
        }
        if (is_null($reference)) {
            parent::__construct(sprintf('The resource %s isn\'t found', $resource));
        } else {
            parent::__construct(sprintf('The resource %s with reference %s isn\'t found', $resource, $reference));
        }
    }
}
