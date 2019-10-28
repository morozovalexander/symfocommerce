<?php

namespace Eshop\ShopBundle\EventListener;

use Doctrine\ORM\EntityRepository;
use Eshop\ShopBundle\Entity\Image;
use Eshop\ShopBundle\Entity\ImageHolderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Eshop\ShopBundle\Service\FileUploader;

class ImageUploadListener
{
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * ImageUploadListener constructor.
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ImageHolderInterface) {
            return;
        }

        $this->uploadFile($entity);
    }

    /**
     * Remove file from server
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ImageHolderInterface && !$entity instanceof Image) {
            return;
        }

        $imageFile = $entity->getImage();

        if ($imageFile instanceof File) {
            $this->uploader->removeUpload($imageFile->getFilename());
        } else {
            $this->uploader->removeUpload($imageFile);
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ImageHolderInterface) {
            return;
        }

        // 'image' not changed
        if (!$args->hasChangedField('image')) {
            return;
        }

        $oldImage = $args->getOldValue('image');

        if ($args->getNewValue('image') === null) {
            // don't overwrite if no file submitted
            $entity->setImage($oldImage);
        } else {
            // remove and upload new file
            $this->uploader->removeUpload($oldImage);
            $this->uploadFile($entity);
        }
    }

    /**
     * Upload image file
     *
     * @param EntityRepository $entity
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof ImageHolderInterface) {
            return;
        }

        $file = $entity->getImage();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setImage($fileName);
    }

    /**
     * Set File instead string to image field
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ImageHolderInterface) {
            return;
        }

        if ($fileName = $entity->getImage()) {
            $entity->setImage(new File($this->uploader->getTargetDir() . '/' . $fileName));
        }
    }
}
