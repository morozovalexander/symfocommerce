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
     * @ORM\OneToOne(targetEntity="Product", inversedBy="featured", fetch="EAGER")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

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
     * Set productOrder
     *
     * @param integer $productOrder
     * @return Featured
     */
    public function setProductOrder($productOrder)
    {
        $this->productOrder = $productOrder;

        return $this;
    }

    /**
     * Get productOrder
     *
     * @return integer 
     */
    public function getProductOrder()
    {
        return $this->productOrder;
    }

    /**
     * Set product
     *
     * @param \Eshop\ShopBundle\Entity\Product $product
     * @return Featured
     */
    public function setProduct(\Eshop\ShopBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Eshop\ShopBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
