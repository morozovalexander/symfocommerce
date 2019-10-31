<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
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
class Slide implements ImageHolderInterface
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

    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() ?? '';
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Slide
     */
    public function setName(string $name): Slide
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param boolean $enabled
     * @return Slide
     */
    public function setEnabled(bool $enabled): Slide
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param integer $slideOrder
     * @return Slide
     */
    public function setSlideOrder(int $slideOrder): Slide
    {
        $this->slideOrder = $slideOrder;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getSlideOrder(): ?int
    {
        return $this->slideOrder;
    }

    /**
     * @inheritdoc
     */
    public function setImage($image): Slide
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getImage()
    {
        return $this->image;
    }
}
