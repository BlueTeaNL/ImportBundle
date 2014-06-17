<?php

namespace Bluetea\ImportBundle\Entity;

use Bluetea\ImportBundle\Model\Import as BaseImport;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Import extends BaseImport
{
    protected $temp;

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->filePath)) {
            // store the old name to delete after the update
            $this->temp = $this->filePath;
            $this->filePath = null;
        } else {
            $this->filePath = 'initial';
        }
    }

    /**
     * Set DateTime to NOW()
     */
    public function updateDatetime()
    {
        $this->setDatetime(new \DateTime());
    }

    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // Generate unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->filePath = $filename.'.'.$this->getFile()->guessExtension();
            $this->name = $this->getFile()->getClientOriginalName();
        }
    }

    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->filePath);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadRootDir().'/'.$this->filePath;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadDir().'/'.$this->filePath;
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return '../../../../../../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/import';
    }
}