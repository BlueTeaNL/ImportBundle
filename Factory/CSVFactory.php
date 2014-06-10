<?php

namespace Bluetea\ImportBundle\Factory;

class CSVFactory extends ImportFactory implements FactoryInterface
{
    /**
     * Parser
     *
     * @throws \Exception
     * @return mixed
     */
    public function parse()
    {
        parent::parse();

        $fileObj = new \SplFileObject($this->importEntity->getFilePath());
        $fileObj->setFlags(\SplFileObject::READ_CSV);
        $fileObj->setCsvControl(
            $this->options['delimiter'],
            $this->options['enclosure'],
            $this->options['escape']
        );

        return $fileObj;
    }
}