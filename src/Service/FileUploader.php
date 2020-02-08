<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 * @package App\Service
 */
class FileUploader
{
    /** @var string */
    private $targetDir;

    /**
     * FileUploader constructor.
     * @param string $targetDir
     */
    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * Upload new file to server
     *
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid('random', true)) . '.' . $file->guessExtension();
        $file->move($this->targetDir, $fileName);

        return $fileName;
    }

    /**
     * Remove file from server
     *
     * @param string $fileName
     * @return bool
     */
    public function removeUpload($fileName): bool
    {
        $fullPath = $this->targetDir . DIRECTORY_SEPARATOR . $fileName;

        if (is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    /**
     * @return string
     */
    public function getTargetDir(): string
    {
        return $this->targetDir;
    }
}
