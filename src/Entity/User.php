<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", length=1000)
     */
    protected $address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="joinDate", type="datetime")
     */
    private $joinDate;

    /**
     * @var Favourites[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Favourites", mappedBy="user")
     **/
    private $favourites;

    /**
     * @var Orders[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Orders", mappedBy="user")
     **/
    private $orders;

    public function __construct()
    {
        parent::__construct();
        $this->joinDate = new \DateTime();
        $this->favourites = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    /**
     * @param \DateTime $joinDate
     * @return User
     */
    public function setJoinDate(\DateTime $joinDate): User
    {
        $this->joinDate = $joinDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getJoinDate(): \DateTime
    {
        return $this->joinDate;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param Favourites $favourites
     * @return User
     */
    public function addFavourite(Favourites $favourites): User
    {
        $this->favourites[] = $favourites;
        return $this;
    }

    /**
     * @param Favourites $favourites
     * @return User
     */
    public function removeFavourite(Favourites $favourites): User
    {
        $this->favourites->removeElement($favourites);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getFavourites(): Collection
    {
        return $this->favourites;
    }

    /**
     * @param Orders $orders
     * @return User
     */
    public function addOrder(Orders $orders): User
    {
        $this->orders[] = $orders;
        return $this;
    }

    /**
     * @param Orders $orders
     * @return User
     */
    public function removeOrder(Orders $orders): User
    {
        $this->orders->removeElement($orders);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone(string $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $address
     * @return User
     */
    public function setAddress(string $address): User
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }
}
