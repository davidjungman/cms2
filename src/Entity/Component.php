<?php

namespace App\Entity;

use App\Util\Maintainable;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComponentRepository")
 */
class Component
{
    const ITEMS_PER_PAGE = 10;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $enabledAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="integer")
     */
    private $average_price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Settings", inversedBy="components")
     */
    private $settings;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRequired;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $componentName;

    /**
     * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function getUpdatedAt():DateTime
    {
        return $this->updatedAt;
    }

    public function getCreatedAt():DateTime
    {
        return $this->createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEnabledAt(): ?\DateTimeInterface
    {
        return $this->enabledAt;
    }

    public function setEnabledAt(?\DateTimeInterface $enabledAt): self
    {
        $this->enabledAt = $enabledAt;

        return $this;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getAveragePrice(): ?int
    {
        return $this->average_price;
    }

    public function setAveragePrice(int $average_price): self
    {
        $this->average_price = $average_price;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getSettings(): ?Settings
    {
        return $this->settings;
    }

    public function setSettings(?Settings $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getComponentName(): ?string
    {
        return $this->componentName;
    }

    public function setComponentName(string $componentName): self
    {
        $this->componentName = $componentName;

        return $this;
    }

    // custom methods for manipulating with component statuses

    public function enableComponent()
    {
        $this->setIsRequired(false);
        $this->setEnabled(true);
        $this->setEnabledAt(new DateTime());
    }

    public function disableComponent():void
    {
        $this->setEnabled(false);
    }

    public function requireComponent():void
    {
        $this->setIsRequired(true);
    }

    public function isEnabled():bool
    {
        return $this->enabled;
    }

    public function canBeEnabled():bool
    {
        if($this->getEnabledAt() != null) return true;
        return false;
    }

    public function isRequired():bool
    {
        return $this->isRequired;
    }

    public function isDisabled():bool
    {
        return (!$this->isEnabled() && !$this->isRequired);
    }
}
