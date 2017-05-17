<?php

namespace Eshop\ShopBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * Upload new file to server
     *
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->targetDir, $fileName);

        return $fileName;
    }

    /**
     * Remove file from server
     *
     * @param string $fileName
     * @return bool
     */
    public function removeUpload($fileName)
    {
        $fullPath = $this->targetDir . DIRECTORY_SEPARATOR . $fileName;

        if (is_file($fullPath)){
            return unlink($fullPath);
        }
        return false;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
