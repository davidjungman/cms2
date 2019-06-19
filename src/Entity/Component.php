<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string", length=255, unique=true)
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
    private $averagePrice;

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

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isCoreComponent;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isStandaloneComponent;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Component", inversedBy="dependantComponents")
     * @ORM\JoinTable(name="dependencies",
     *     joinColumns={@ORM\JoinColumn(name="dependency_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dependant_id", referencedColumnName="id")}
     *     )
     */
    private $dependencies;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Component", mappedBy="dependencies")
     *
     */
    private $dependantComponents;

    public function getDependencies()
    {
        return $this->dependencies;
    }

    public function getDependantComponents()
    {
        return $this->dependantComponents;
    }

    public function addDependency(Component $component)
    {
        if($this->getDependencies()->contains($component))
        {
            return $this;
        }
        $this->setIsStandaloneComponent(false);
        $this->dependencies->add($component);
        return $this;
    }

    public function setDependencies(ArrayCollection $arrayCollection)
    {
        if(!$arrayCollection->isEmpty()) $this->setIsStandaloneComponent(false);
        $this->dependencies = $arrayCollection;
    }

    public function __construct()
    {
        $this->setEnabled(false);
        $this->setVersion("1.0.0");
        $this->setIsRequired(false);

        $this->dependencies = new ArrayCollection();
        $this->dependantComponents = new ArrayCollection();
    }

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

    public function getEnabledAt(): ?DateTimeInterface
    {
        return $this->enabledAt;
    }

    public function setEnabledAt(?DateTimeInterface $enabledAt): self
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
        return $this->averagePrice;
    }

    public function setAveragePrice(int $averagePrice): self
    {
        $this->averagePrice = $averagePrice;

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

    public function getIsCoreComponent(): ?bool
    {
        return $this->isCoreComponent;
    }

    public function setIsCoreComponent(bool $isCoreComponent): self
    {
        $this->isCoreComponent = $isCoreComponent;

        return $this;
    }

    public function getIsStandaloneComponent(): ?bool
    {
        return $this->isStandaloneComponent;
    }

    public function setIsStandaloneComponent(bool $isStandaloneComponent): self
    {
        $this->isStandaloneComponent = $isStandaloneComponent;
        if($isStandaloneComponent == true) $this->dependencies = new ArrayCollection();

        return $this;
    }

    public function getTotalPrice(): int
    {
        if($this->getIsStandaloneComponent() || $this->getDependencies()->isEmpty())
        {
            return $this->averagePrice;
        }

        $price = 0;
        $price += $this->getAveragePrice();
        foreach($this->getDependencies() as $dependency)
        {
            $price += $dependency->getAveragePrice();
        }
        return $price;
    }
}
