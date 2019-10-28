<?php

namespace Eshop\ShopBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Eshop\ShopBundle\Entity\Image;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use Symfony\Component\HttpFoundation\File\File;

class UploadListener
{
    /** @var EntityManager */
    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PostUploadEvent $event
     * @return ResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
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
