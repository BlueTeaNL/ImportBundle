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

    public function add($message, $type = ImportLogger::INFO, $extended = true)
    {
        $this->log[] = array (
            'datetime' => new \DateTime(),
            'type' => $type,
            'message' => $message,
            'extended' => $extended
        );
    }

    public function addLine()
    {
        $this->add('=======================================================', ImportLogger::INFO, false);
    }

    public function getLog()
    {
        return $this->parseLog();
    }

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
    public function getStatistics()
    {
        $percErrors = 0;
        $nrErrors = array_sum($this->counter);
        if ($nrErrors > 0) {
            $percErrors =  round(($this->counter['error'] / $nrErrors) * 100, 1);
        }

        return array(
            "added" => $this->counter['added'],
            "updated" => $this->counter['updated'],
            "deleted" => $this->counter['deleted'],
            "unchanged" => $this->counter['unchanged'],
            "skipped" => $this->counter['skipped'],
            "error" => $this->counter['error'],
            "total" => $nrErrors,
            "errorPercentage" => $percErrors
        );
    }

    public function countAdded($count = 1)
    {
        $this->counter['added'] += $count;
    }

    public function countUpdated($count = 1)
    {
        $this->counter['updated'] += $count;
    }

    public function countDeleted($count = 1)
    {
        $this->counter['deleted'] += $count;
    }

    public function countError($count = 1)
    {
        $this->counter['error'] += $count;
    }

    public function countSkipped($count = 1)
    {
        $this->counter['skipped'] += $count;
    }

    public function countUnchanged($count = 1)
    {
        $this->counter['unchanged'] += $count;
    }

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