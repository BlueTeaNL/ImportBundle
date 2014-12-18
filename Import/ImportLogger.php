<?php

namespace Bluetea\ImportBundle\Import;

class ImportLogger
{
    /**
     * Constant debug types
     */
    const DEBUG = 'Debug';
    const INFO = 'Info';
    const WARNING = 'Warning';
    const ERROR = 'Error';
    const CRITICAL = 'Critical';

    /**
     * @var array Log messages
     */
    protected $log = array();

    /**
     * @var array
     */
    protected $counter = array (
        'added' => 0,
        'updated' => 0,
        'deleted' => 0,
        'error' => 0,
        'skipped' => 0,
        'unchanged' => 0
    );

    /**
     * Add message to the log
     *
     * @param $message
     * @param string $type
     * @param bool $extended
     */
    public function add($message, $type = ImportLogger::INFO, $extended = true)
    {
        $this->log[] = array (
            'datetime' => new \DateTime(),
            'type' => $type,
            'message' => $message,
            'extended' => $extended
        );
    }

    /**
     * Add line to the log
     */
    public function addLine()
    {
        $this->add('=======================================================', ImportLogger::INFO, false);
    }

    /**
     * Get the log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->parseLog();
    }

    /**
     * Parse log and return a string
     *
     * @return string
     */
    protected function parseLog()
    {
        $log = '';
        if (count($this->log) > 0) {
            foreach ($this->log as $logEntry) {
                if ($logEntry['extended']) {
                    $log .= sprintf(
                        '[%s] %s: %s<br />',
                        $logEntry['datetime']->format('Y-m-d H:i:s'), // DateTime object
                        $logEntry['type'],
                        $logEntry['message']
                    );
                } else {
                    $log .= sprintf(
                        '%s<br />',
                        $logEntry['message']
                    );
                }
            }
        }
        return $log;
    }

    /**
     * Log statistics in the import log
     *
     * @deprecated Removed in v2.0
     */
    public function logStatistics()
    {
        $counter = $this->getStatistics();
        $this->addLine();
        $this->add(sprintf(
            'Added: %s, Updated: %s, Deleted: %s, Unchanged: %s, Skipped: %s, Error(s): %s, Total: %s, Error percentage: %s %%',
            $counter['added'],
            $counter['updated'],
            $counter['deleted'],
            $counter['unchanged'],
            $counter['skipped'],
            $counter['error'],
            $counter['total'],
            $counter['errorPercentage']
        ));
        $this->addLine();
    }

    /**
     * Get the log statistics
     *
     * @param null|string $field
     * @param bool $calculatePercentage
     * @return array
     */
    public function getStatistics($field = null, $calculatePercentage = true)
    {
        $percErrors = 0;
        $nrErrors = array_sum($this->counter);

        if ($nrErrors > 0 && $calculatePercentage == true) {
            $percErrors =  round(($this->counter['error'] / $nrErrors) * 100, 1);
        }

        $statistics = array(
            "added" => $this->counter['added'],
            "updated" => $this->counter['updated'],
            "deleted" => $this->counter['deleted'],
            "unchanged" => $this->counter['unchanged'],
            "skipped" => $this->counter['skipped'],
            "error" => $this->counter['error'],
            "total" => $nrErrors,
            "errorPercentage" => $percErrors
        );

        if (is_null($field)) {
            return $statistics;
        } else {
            if (array_key_exists($field, $statistics)) {
                return $statistics[$field];
            }
        }

        return false;
    }

    /**
     * Increase added counter
     *
     * @param int $count
     */
    public function countAdded($count = 1)
    {
        $this->counter['added'] += $count;
    }

    /**
     * Increase updated counter
     *
     * @param int $count
     */
    public function countUpdated($count = 1)
    {
        $this->counter['updated'] += $count;
    }

    /**
     * Increase deleted counter
     *
     * @param int $count
     */
    public function countDeleted($count = 1)
    {
        $this->counter['deleted'] += $count;
    }

    /**
     * Increase error counter
     *
     * @param int $count
     */
    public function countError($count = 1)
    {
        $this->counter['error'] += $count;
    }

    /**
     * Increase skipped counter
     *
     * @param int $count
     */
    public function countSkipped($count = 1)
    {
        $this->counter['skipped'] += $count;
    }

    /**
     * Increase unchanged counter
     *
     * @param int $count
     */
    public function countUnchanged($count = 1)
    {
        $this->counter['unchanged'] += $count;
    }

    /**
     * Get the error count
     *
     * @deprecated Removed in v2.0, use getStatistics instead
     * @return mixed
     */
    public function getErrorCount()
    {
        return $this->counter['error'];
    }

    public function resetLog()
    {
        $this->counter = array (
            'added' => 0,
            'updated' => 0,
            'deleted' => 0,
            'error' => 0,
            'skipped' => 0,
            'unchanged' => 0
        );
        $this->log = array();
    }
}
