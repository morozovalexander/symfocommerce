<?php

namespace Eshop\ShopBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Eshop\ShopBundle\Repository\CategoryRepository")
 * @UniqueEntity("slug"),
 *     errorPath="slug",
 *     message="This slug is already in use."
 * @ORM\HasLifecycleCallbacks()
 */
class Category
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
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

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
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     **/
    private $products;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = $this->dateCreated;
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
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
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add products
     *
     * @param \Eshop\ShopBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\Eshop\ShopBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Eshop\ShopBundle\Entity\Product $products
     */
    public function removeProduct(\Eshop\ShopBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set metaKeys
     *
     * @param string $metaKeys
     * @return Category
     */
    public function setMetaKeys($metaKeys)
    {
        $this->metaKeys = $metaKeys;

        return $this;
    }

    /**
     * Get metaKeys
     *
     * @return string
     */
    public function getMetaKeys()
    {
        return $this->metaKeys;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Category
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add children
     *
     * @param \Eshop\ShopBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(\Eshop\ShopBundle\Entity\Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Eshop\ShopBundle\Entity\Category $children
     */
    public function removeChild(\Eshop\ShopBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Eshop\ShopBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Eshop\ShopBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Eshop\ShopBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Category
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return Category
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Category
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
