<?php

namespace Eshop\ShopBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Slide
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("slideOrder")
 * @ORM\HasLifecycleCallbacks()
 */
class Slide
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="slide_order", type="integer", unique=true)
     */
    private $slideOrder;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/bmp" })
     */
    private $image;

    public function __construct() {
        $this->enabled = true;
    }

    public function __toString(){
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Slide
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Slide
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set slideOrder
     *
     * @param integer $slideOrder
     * @return Slide
     */
    public function setSlideOrder($slideOrder)
    {
        $this->slideOrder = $slideOrder;

        return $this;
    }

    /**
     * Get slideOrder
     *
     * @return integer 
     */
    public function getSlideOrder()
    {
        return $this->slideOrder;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Slide
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
