<?php

namespace Eshop\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * StaticPage
 *
 * @ORM\Table(name="static_page")
 * @ORM\Entity(repositoryClass="Eshop\ShopBundle\Repository\StaticPageRepository")
 * @UniqueEntity("orderNum")
 */
class StaticPage
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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="orderNum", type="integer", unique=true)
     */
    private $orderNum;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

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
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return StaticPage
     */
    public function setTitle(string $title): StaticPage
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $content
     * @return StaticPage
     */
    public function setContent(string $content): StaticPage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param boolean $enabled
     * @return StaticPage
     */
    public function setEnabled(bool $enabled): StaticPage
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param integer $orderNum
     * @return StaticPage
     */
    public function setOrderNum(int $orderNum): StaticPage
    {
        $this->orderNum = $orderNum;
        return $this;
    }

    /**
     * @return integer
     */
    public function getOrderNum(): int
    {
        return $this->orderNum;
    }

    /**
     * @param string $slug
     * @return StaticPage
     */
    public function setSlug(string $slug): StaticPage
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $metaKeys
     * @return StaticPage
     */
    public function setMetaKeys(string $metaKeys): StaticPage
    {
        $this->metaKeys = $metaKeys;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKeys(): string
    {
        return $this->metaKeys;
    }

    /**
     * @param string $metaDescription
     * @return StaticPage
     */
    public function setMetaDescription(string $metaDescription): StaticPage
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }
}
