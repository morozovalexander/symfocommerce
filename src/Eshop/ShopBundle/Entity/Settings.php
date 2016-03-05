<?php

namespace Eshop\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="Eshop\ShopBundle\Repository\SettingsRepository")
 */
class Settings
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
     * @var bool
     *
     * @ORM\Column(name="show_empty_categories", type="boolean", nullable=true)
     */
    private $showEmptyCategories;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_empty_manufacturers", type="boolean", nullable=true)
     */
    private $showEmptyManufacturers;


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
     * Set showEmptyCategories
     *
     * @param boolean $showEmptyCategories
     * @return Settings
     */
    public function setShowEmptyCategories($showEmptyCategories)
    {
        $this->showEmptyCategories = $showEmptyCategories;

        return $this;
    }

    /**
     * Get showEmptyCategories
     *
     * @return boolean 
     */
    public function getShowEmptyCategories()
    {
        return $this->showEmptyCategories;
    }

    /**
     * Set showEmptyManufacturers
     *
     * @param boolean $showEmptyManufacturers
     * @return Settings
     */
    public function setShowEmptyManufacturers($showEmptyManufacturers)
    {
        $this->showEmptyManufacturers = $showEmptyManufacturers;

        return $this;
    }

    /**
     * Get showEmptyManufacturers
     *
     * @return boolean 
     */
    public function getShowEmptyManufacturers()
    {
        return $this->showEmptyManufacturers;
    }
}
