<?php

namespace Eshop\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Eshop\UserBundle\Repository\UserRepository")
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
     * @ORM\Column(type="string", length=255)
     *
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    protected $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="joinDate", type="datetime")
     */
    private $joinDate;

    /**
     * @ORM\OneToMany(targetEntity="\Eshop\ShopBundle\Entity\Favourites", mappedBy="user")
     **/
    private $favourites;

    /**
     * @ORM\OneToMany(targetEntity="\Eshop\ShopBundle\Entity\Orders", mappedBy="user")
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
     * Set joinDate
     *
     * @param \DateTime $joinDate
     * @return User
     */
    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    /**
     * Get joinDate
     *
     * @return \DateTime 
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Add favourites
     *
     * @param \Eshop\ShopBundle\Entity\Favourites $favourites
     * @return User
     */
    public function addFavourite(\Eshop\ShopBundle\Entity\Favourites $favourites)
    {
        $this->favourites[] = $favourites;

        return $this;
    }

    /**
     * Remove favourites
     *
     * @param \Eshop\ShopBundle\Entity\Favourites $favourites
     */
    public function removeFavourite(\Eshop\ShopBundle\Entity\Favourites $favourites)
    {
        $this->favourites->removeElement($favourites);
    }

    /**
     * Get favourites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFavourites()
    {
        return $this->favourites;
    }

    /**
     * Add orders
     *
     * @param \Eshop\ShopBundle\Entity\Orders $orders
     * @return User
     */
    public function addOrder(\Eshop\ShopBundle\Entity\Orders $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Eshop\ShopBundle\Entity\Orders $orders
     */
    public function removeOrder(\Eshop\ShopBundle\Entity\Orders $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
