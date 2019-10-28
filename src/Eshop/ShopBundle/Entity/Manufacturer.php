<?php

namespace Eshop\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Manufacturer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Eshop\ShopBundle\Repository\ManufacturerRepository")
 * @UniqueEntity("slug"),
 *     errorPath="slug",
 *     message="This slug is already in use."
 * @ORM\HasLifecycleCallbacks()
 */
class Manufacturer implements ImageHolderInterface
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
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     */

    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keys", type="text", nullable=true)
     */
    private $metaKeys;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/bmp" })
     */
    private $image;

    /**
     * @var Product[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="manufacturer")
     */
    private $products;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = $this->dateCreated;
        $this->products = new ArrayCollection();
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
     * @return Manufacturer
     */
    public function setName(string $name): Manufacturer
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
     * @param string $description
     * @return Manufacturer
     */
    public function setDescription(string $description): Manufacturer
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param Product $products
     * @return Manufacturer
     */
    public function addProduct(Product $products): Manufacturer
    {
        $this->products[] = $products;
        return $this;
    }

    /**
     * @param Product $products
     * @return Manufacturer
     */
    public function removeProduct(Product $products): Manufacturer
    {
        $this->products->removeElement($products);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getProducts(): ?Collection
    {
        return $this->products;
    }

    /**
     * @param string $metaKeys
     * @return Manufacturer
     */
    public function setMetaKeys(string $metaKeys): Manufacturer
    {
        $this->metaKeys = $metaKeys;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaKeys(): ?string
    {
        return $this->metaKeys;
    }

    /**
     * @param string $metaDescription
     * @return Manufacturer
     */
    public function setMetaDescription(string $metaDescription): Manufacturer
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $slug
     * @return Manufacturer
     */
    public function setSlug(string $slug): Manufacturer
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param \DateTime $dateCreated
     * @return Manufacturer
     */
    public function setDateCreated(\DateTime $dateCreated): Manufacturer
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateUpdated
     * @return Manufacturer
     */
    public function setDateUpdated(\DateTime $dateUpdated): Manufacturer
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdated(): \DateTime
    {
        return $this->dateUpdated;
    }

    /**
     * @inheritdoc
     */
    public function setImage($image): Manufacturer
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
