<?php

namespace Bluetea\ImportBundle\Monolog\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ImportLogger extends Logger
{
    /**
     * Import line added
     */
    const ADDED = 'added';

    /**
     * Import line updated
     */
    const UPDATED = 'updated';

    /**
     * Import line deleted
     */
    const DELETED = 'deleted';

    /**
     * Import line errors
     */
    const ERROR = 'error';

    /**
     * Import line skipped
     */
    const SKIPPED = 'skipped';

    /**
     * Import line not changed
     */
    const UNCHANGED = 'unchanged';

    protected $counter = array(
        'added' => 0,
        'updated' => 0,
        'deleted' => 0,
        'error' => 0,
        'skipped' => 0,
        'unchanged' => 0
    );

    /**
     * Add amount to the counter
     *
     * @param $count
     * @param $amount
     */
    public function addCount($count, $amount)
    {
        $this->counter[$count] += $amount;
    }

    public function countAdded($amount = 1)
    {
        $this->addCount(self::ADDED, $amount);
    }

    public function countUpdated($amount = 1)
    {
        $this->addCount(self::UPDATED, $amount);
    }

    public function countDeleted($amount = 1)
    {
        $this->addCount(self::DELETED, $amount);
    }

    public function countError($amount = 1)
    {
        $this->addCount(self::ERROR, $amount);
    }

    public function countSkipped($amount = 1)
    {
        $this->addCount(self::SKIPPED, $amount);
    }

    public function countUnchanged($amount = 1)
    {
        $this->addCount(self::UNCHANGED, $amount);
    }
}