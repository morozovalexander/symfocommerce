<?php
namespace Eshop\ShopBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Eshop\ShopBundle\Entity\Image;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Symfony\Component\HttpFoundation\File\File;

class UploadListener
{
    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function onUpload(PostUploadEvent $event)
    {
        /**
         * @var File $file
         */
        $file = $event->getFile();

        $image = new Image();
        $image->setPath($file->getFilename());

        $this->manager->persist($image);
        $this->manager->flush();

        $response = $event->getResponse();
        $response['image_id'] = $image->getId();

        return $response;
    }
}
