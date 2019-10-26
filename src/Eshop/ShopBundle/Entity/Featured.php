<?php

namespace Eshop\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Featured
 *
 * @ORM\Table(name="featured")
 * @ORM\Entity(repositoryClass="Eshop\ShopBundle\Repository\FeaturedRepository")
 */
class Featured
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="product_order", type="integer", unique=true)
     */
    private $productOrder;

    /**
     * @var Product
     *
     * @ORM\OneToOne(targetEntity="Product", inversedBy="featured", fetch="EAGER")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $productOrder
     * @return Featured
     */
    public function setProductOrder(int $productOrder): Featured
    {
        $this->productOrder = $productOrder;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductOrder(): int
    {
        return $this->productOrder;
    }

    /**
     * @param Product $product
     * @return Featured
     */
    public function setProduct(Product $product): Featured
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}
