<?php

namespace Eshop\ShopBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
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
class Category implements ImageHolderInterface
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
     * @var Category[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @var Category
     *
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
     * @var Product[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = $this->dateCreated;
        $this->products = $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName() ?? '';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName(string $name): Category
    {
        $this->name = $name;
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
     * @param string $description
     * @return Category
     */
    public function setDescription(string $description): Category
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param Product $products
     * @return Category
     */
    public function addProduct(Product $products): Category
    {
        $this->products[] = $products;
        return $this;
    }

    /**
     * @param Product $products
     * @return Category
     */
    public function removeProduct(Product $products): Category
    {
        $this->products->removeElement($products);
        return $this;
    }

    /**
     * @return Product[]|Collection
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param string $metaKeys
     * @return Category
     */
    public function setMetaKeys($metaKeys): Category
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
     * @return Category
     */
    public function setMetaDescription($metaDescription): Category
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
     * @return Category
     */
    public function setSlug($slug): Category
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
     * @param Category $children
     * @return Category
     */
    public function addChild(Category $children): Category
    {
        $this->children[] = $children;
        return $this;
    }

    /**
     * @param Category $children
     * @return Category
     */
    public function removeChild(Category $children): Category
    {
        $this->children->removeElement($children);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent): Category
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Category
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param \DateTime $dateCreated
     * @return Category
     */
    public function setDateCreated($dateCreated): Category
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
     * @return Category
     */
    public function setDateUpdated($dateUpdated): Category
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
    public function setImage($image): Category
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
