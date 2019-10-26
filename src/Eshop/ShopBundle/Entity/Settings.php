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
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param boolean $showEmptyCategories
     * @return Settings
     */
    public function setShowEmptyCategories(bool $showEmptyCategories): Settings
    {
        $this->showEmptyCategories = $showEmptyCategories;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowEmptyCategories(): bool
    {
        return $this->showEmptyCategories;
    }

    /**
     * @param boolean $showEmptyManufacturers
     * @return Settings
     */
    public function setShowEmptyManufacturers(bool $showEmptyManufacturers): Settings
    {
        $this->showEmptyManufacturers = $showEmptyManufacturers;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowEmptyManufacturers(): bool
    {
        return $this->showEmptyManufacturers;
    }
}
