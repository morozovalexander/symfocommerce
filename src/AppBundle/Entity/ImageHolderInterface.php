<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;

interface ImageHolderInterface
{
    /**
     * Set image filename
     * @param string|File $image
     */
    public function setImage($image);

    /**
     * Get image filename
     */
    public function getImage();
}
