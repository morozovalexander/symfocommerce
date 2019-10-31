<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Measure
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Measure
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
     * @var Product[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="measure")
     */
    private $products;

    public function __construct()
    {
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
     * @return Measure
     */
    public function setName(string $name): Measure
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
     * @param Product $products
     * @return Measure
     */
    public function addProduct(Product $products): Measure
    {
        $this->products[] = $products;
        return $this;
    }

    /**
     * @param Product $products
     * @return Measure
     */
    public function removeProduct(Product $products): Measure
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
}
