<?php

namespace Bluetea\ImportBundle\Doctrine;

use Bluetea\ImportBundle\Model\ImportLogInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Bluetea\ImportBundle\Model\ImportLogManager as BaseImportLogManager;

class ImportLogManager extends BaseImportLogManager
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
     * Deletes an ImportLog
     *
     * @param ImportLogInterface $ImportLog
     */
    public function deleteImportLog(ImportLogInterface $ImportLog)
    {
        $this->objectManager->remove($ImportLog);
        $this->objectManager->flush();
    }

    /**
     * Find an import log by id
     *
     * @param int $id
     * @return ImportLogInterface
     */
    public function findImportLog($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Find an import log by the given criteria
     *
     * @param array $criteria
     * @return ImportLogInterface
     */
    public function findImportLogBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find import logs by the given criteria
     *
     * @param array $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     * @return ImportLogInterface
     */
    public function findImportLogsBy(array $criteria, $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Returns a collection with all ImportLogs
     *
     * @return ImportLogInterface[]
     */
    public function findImportLogs()
    {
        return $this->repository->findAll();
    }

    /**
     * Returns the ImportLog's fully qualified class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Updates an ImportLog
     *
     * @param ImportLogInterface $ImportLog
     * @param bool $andFlush
     * @return ImportLogInterface
     */
    public function updateImportLog(ImportLogInterface $ImportLog, $andFlush = true)
    {
        $this->objectManager->persist($ImportLog);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}