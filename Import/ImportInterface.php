<?php

namespace Bluetea\ImportBundle\Import;

use Bluetea\ImportBundle\Factory\FactoryInterface;
use Bluetea\ImportBundle\Import\ImportLogger;
use Doctrine\Common\Persistence\ObjectManager;

interface ImportInterface
{
    /**
     * __construct
     *
     * @param object $container
     * @param \Doctrine\Common\Persistence\ObjectManager $om
     */
    public function __construct($container, ObjectManager $om);

    /**
     * This import method is called in the Import service and performs the 'real' import
     * To start looping trough the lines you need to call the factory parser. To call the parser
     * in the import, it's even possible to change the options if necessary.
     * Example: $this->factory->parse($this->filePath, $this->options)
     *
     * The parse method from the factory returns an array which you can loop trough.
     * Use the list() function from php to assign the fields to variables
     * Example: list($var1, $var) = $line
     *
     * If you're using caching (recommended) you have to define a cachId. This cachId must be passed
     * to the select method in the custom repository so it can be refreshed if the entity is updated.
     * Example: $cacheId = "HospitalizationMethod_fetchByReference_" . $reference;
     *
     * If you queried the repository and got an entity, clone this to the $orignalEntity variable. This
     * variable is needed in the persistAndFlush method to calculate if it's changed or not. If it isn't
     * changed we don't have to perform and update or insert.
     * Example: $originalEntity = clone $entity;
     *
     * If you created or updated the entity, persist en flush it, but to do it smart call the
     * persistAndFlush method which is located in the BaseImport class.
     * Example: $this->persistAndFlush($entity, $originalEntity, $cacheId);
     *
     * @return boolean
     */
    public function import();

    public function importLine($line);

    public function getLogger();

    public function setLogger(ImportLogger $logger);

    public function getFactory();

    public function setFactory(FactoryInterface $factory);
}