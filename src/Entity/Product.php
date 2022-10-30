<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("slug"),
 *     errorPath="slug",
 *     message="This slug is already in use."
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Must be at least {{ limit }}",
     * )
     */
    private $quantity;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="measure_quantity", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Must be at least {{ limit }}",
     * )
     */
    private $measureQuantity;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @var Manufacturer
     *
     * @ORM\ManyToOne(targetEntity="Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $manufacturer;

    /**
     * @var Image[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="product", cascade={"remove"})
     */
    private $images;

    /**
     * @var OrderProduct[]|Collection
     *
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="product")
     */
    private $productOrders;

    /**
     * @var Measure
     *
     * @ORM\ManyToOne(targetEntity="Measure", inversedBy="products")
     * @ORM\JoinColumn(name="measure_id", referencedColumnName="id")
     */
    private $measure;

    /**
     * @var Favourites[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Favourites", mappedBy="product")
     */
    private $favourites;

    /**
     * @var Featured
     *
     * @ORM\OneToOne(targetEntity="Featured", mappedBy="product")
     */
    private $featured;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = $this->dateCreated;
        $this->productOrders = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->favourites = new ArrayCollection();
        $this->quantity = 1;
        $this->deleted = false;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() ?? '';
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->dateUpdated = new \DateTime();
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
     * @return Product
     */
    public function setName(string $name): Product
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
     * @return Product
     */
    public function setDescription(string $description): Product
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
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param Category $category
     * @return Product
     */
    public function setCategory(Category $category): Product
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Manufacturer $manufacturer
     * @return Product
     */
    public function setManufacturer(Manufacturer $manufacturer): Product
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return Manufacturer|null
     */
    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    /**
     * @param Image $images
     * @return Product
     */
    public function addImage(Image $images): Product
    {
        $this->images[] = $images;
        return $this;
    }

    /**
     * @param Image $images
     */
    public function removeImage(Image $images): void
    {
        $this->images->removeElement($images);
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param OrderProduct $productOrders
     * @return Product
     */
    public function addProductOrder(OrderProduct $productOrders): Product
    {
        $this->productOrders[] = $productOrders;
        return $this;
    }

    /**
     * @param OrderProduct $productOrders
     * @return Product
     */
    public function removeProductOrder(OrderProduct $productOrders): Product
    {
        $this->productOrders->removeElement($productOrders);
        return $this;
    }

    /**
     * @return OrderProduct[]|Collection
     */
    public function getProductOrders(): Collection
    {
        return $this->productOrders;
    }

    /**
     * @param string $metaKeys
     * @return Product
     */
    public function setMetaKeys($metaKeys): Product
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
     * @return Product
     */
    public function setMetaDescription($metaDescription): Product
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
     * @param integer $quantity
     * @return Product
     */
    public function setQuantity($quantity): Product
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return integer
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param integer $measureQuantity
     * @return Product
     */
    public function setMeasureQuantity(int $measureQuantity): Product
    {
        $this->measureQuantity = $measureQuantity;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getMeasureQuantity(): ?int
    {
        return $this->measureQuantity;
    }

    /**
     * @param Measure $measure
     * @return Product
     */
    public function setMeasure(Measure $measure): Product
    {
        $this->measure = $measure;
        return $this;
    }

    /**
     * @return Measure|null
     */
    public function getMeasure(): ?Measure
    {
        return $this->measure;
    }

    /**
     * @param string $slug
     * @return Product
     */
    public function setSlug(string $slug): Product
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
     * @return Product
     */
    public function setDateCreated(\DateTime $dateCreated): Product
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
     * @return Product
     */
    public function setDateUpdated(\DateTime $dateUpdated): Product
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
     * @param Favourites $favourites
     * @return Product
     */
    public function addFavourite(Favourites $favourites): Product
    {
        $this->favourites[] = $favourites;
        return $this;
    }

    /**
     * @param Favourites $favourites
     * @return Product
     */
    public function removeFavourite(Favourites $favourites): Product
    {
        $this->favourites->removeElement($favourites);
        return $this;
    }

    /**
     * @return Favourites[]|Collection
     */
    public function getFavourites(): Collection
    {
        return $this->favourites;
    }

    /**
     * @param Featured $featured
     * @return Product
     */
    public function setFeatured(Featured $featured): Product
    {
        $this->featured = $featured;
        return $this;
    }

    /**
     * @return Featured
     */
    public function getFeatured(): ?Featured
    {
        return $this->featured;
    }

    /**
     * @param boolean $deleted
     * @return Product
     */
    public function setDeleted($deleted): Product
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDeleted(): bool
    {
        return $this->deleted;
    }
}
