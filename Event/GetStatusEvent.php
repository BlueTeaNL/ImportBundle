<?php

namespace Bluetea\ImportBundle\Event;

class GetStatusEvent extends ImportEvent
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}