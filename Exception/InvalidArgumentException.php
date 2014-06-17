<?php

namespace Bluetea\ImportBundle\Exception;

/**
 * Thrown when an argument isn't valid
 */
class InvalidArgumentException extends ImportException
{
    /**
     * Constructor.
     *
     * @param string $argument The argument
     * @param string $field The field name
     * @param null|string $type
     */
    public function __construct($argument, $field, $type = null)
    {
        if (!is_null($type)) {
            parent::__construct(sprintf('The argument %s isn\'t valid for field %s. Expecting argument of type %s', $argument, $field, $type));
        } else {
            parent::__construct(sprintf('The argument %s isn\'t valid for field %s', $argument, $field));
        }
    }
}
