<?php
/**
 * Created by PhpStorm.
 * Date: 29.01.2024
 * Time: 21:49
 */

namespace App\Domain\Product;

use App\Domain\Location\Location;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class Product
{// TODO create relation to location (many to many)
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToMany(targetEntity: Location::class, inversedBy: 'productList')]
    private Collection $locationList;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $durationDays;

    #[ORM\Column(type: Types::STRING)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $description = '';

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $enabled = true;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Price::class, cascade: ['persist'])]
    #[ORM\OrderBy(['enabled' => 'DESC', 'createdAt' => 'DESC'])]
    private Collection $priceList;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->locationList = new ArrayCollection();
        $this->priceList = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLocationList(): Collection
    {
        return $this->locationList;
    }

    public function hasLocation(Location $location): bool
    {
        return true === $this->locationList->contains($location);
    }

    public function getDurationDays(): int
    {
        return $this->durationDays;
    }

    public function setDurationDays(int $durationDays): self
    {
        $this->durationDays = $durationDays;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function addPrice(Price $price): self
    {
        $this->priceList->add($price);
        $price->setProduct($this);

        return $this;
    }

    public function getPriceList(): Collection
    {
        return $this->priceList;
    }

    public function getPriceById(string $priceId): null|Price
    {
        $price = $this->getPriceList()->filter(
            function ($price) use ($priceId) {
                return $priceId === $price->getId();
            }
        )->first();

        return false === $price ? null : $price;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
