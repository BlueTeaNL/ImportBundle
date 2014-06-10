<?php

namespace Bluetea\ImportBundle\Doctrine;

use Bluetea\ImportBundle\Model\ImportInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Bluetea\ImportBundle\Model\ImportManager as BaseImportManager;

class ImportManager extends BaseImportManager
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param ObjectManager $om
     * @param $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * Deletes an import
     *
     * @param ImportInterface $import
     */
    public function deleteImport(ImportInterface $import)
    {
        $this->objectManager->remove($import);
        $this->objectManager->flush();
    }

    /**
     * Find a user by the given criteria
     *
     * @param array $criteria
     * @return ImportInterface
     */
    public function findImportBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Returns a collection with all imports
     *
     * @return ImportInterface[]
     */
    public function findImports()
    {
        return $this->repository->findAll();
    }

    /**
     * Returns the import's fully qualified class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Updates an import
     *
     * @param ImportInterface $import
     * @param bool $andFlush
     * @return ImportInterface
     */
    public function updateImport(ImportInterface $import, $andFlush = true)
    {
        $this->objectManager->persist($import);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}