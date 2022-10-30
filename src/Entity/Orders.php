<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Orders
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders
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
     * @var string
     *
     * @ORM\Column(name="comment", type="text", length=500, nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", length=500)
     */
    private $address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var float
     *
     * @ORM\Column(name="sum", type="float")
     */
    private $sum;

    /**
     * @var OrderProduct[]|Collection
     *
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="order")
     */
    private $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
        $this->date = new \DateTime();
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
     * @return Orders
     */
    public function setName($name): Orders
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
     * @param string $phone
     * @return Orders
     */
    public function setPhone(string $phone): Orders
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $email
     * @return Orders
     */
    public function setEmail(string $email): Orders
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param \DateTime $date
     * @return Orders
     */
    public function setDate(\DateTime $date): Orders
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param string $address
     * @return Orders
     */
    public function setAddress(string $address): Orders
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $comment
     * @return Orders
     */
    public function setComment(string $comment): Orders
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param OrderProduct $orderProducts
     * @return Orders
     */
    public function addOrderProduct(OrderProduct $orderProducts): Orders
    {
        $this->orderProducts[] = $orderProducts;
        return $this;
    }

    /**
     * @param OrderProduct $orderProducts
     * @return Orders
     */
    public function removeOrderProduct(OrderProduct $orderProducts): Orders
    {
        $this->orderProducts->removeElement($orderProducts);
        return $this;
    }

    /**
     * @return OrderProduct[]|Collection
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    /**
     * @param User $user
     * @return Orders
     */
    public function setUser(User $user): Orders
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param float $sum
     * @return Orders
     */
    public function setSum(float $sum): Orders
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * @return float
     */
    public function getSum(): float
    {
        return $this->sum;
    }
}
