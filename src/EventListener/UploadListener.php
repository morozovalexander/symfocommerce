<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use Symfony\Component\HttpFoundation\File\File;

class UploadListener
{
    /** @var EntityManagerInterface */
    protected $manager;

    /**
     * UploadListener constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PostUploadEvent $event
     * @return ResponseInterface
     */
    public function onUpload(PostUploadEvent $event): ResponseInterface
    {
        /** @var File $file */
        $file = $event->getFile();

        $image = new Image();
        /**@var Image $image */
        $image->setImage($file->getFilename());

        $this->manager->persist($image);
        $this->manager->flush();

        $response = $event->getResponse();
        $response['image_id'] = $image->getId();

        return $response;
    }
}
