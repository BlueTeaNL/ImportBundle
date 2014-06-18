<?php

namespace Bluetea\ImportBundle\Factory;

class CSVFactory extends ImportFactory implements FactoryInterface
{
    protected $options = [
        'delimiter' => ';',
        'enclosure' => '"',
        'escape' => '\\'
    ];

    /**
     * @var \SplFileObject
     */
    protected $fileObj;

    /**
     * Parser
     *
     * @throws \Exception
     * @return mixed
     */
    public function parse()
    {
        parent::parse();

        $this->fileObj = new \SplFileObject($this->importEntity->getAbsolutePath());
        $this->fileObj->setFlags(\SplFileObject::READ_CSV);
        $this->fileObj->setCsvControl(
            $this->options['delimiter'],
            $this->options['enclosure'],
            $this->options['escape']
        );

        return $this->fileObj;
    }

    /**
     * Return the length (lines) of a file
     *
     * @return integer
     */
    public function getLength()
    {
        // This is a dirty fix but count() isn't support for SplFileObject
        $this->fileObj->seek($this->fileObj->getSize());
        $lastLine = $this->fileObj->key();

        // Now, rewind to the first line
        // CAUTION! This method shouldn't be executed if the current line isn't the first line!
        $this->fileObj->rewind();

        // Return the last key which is the last line key
        return $lastLine;
    }
}