<?php
/**
 * Created by PhpStorm.
 * Date: 07.02.2024
 * Time: 23:38
 */

namespace App\Domain\Location;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
class Location
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'regularScheduler', targetEntity: RegularScheduler::class, cascade: ['persist'])]
    #[ORM\OrderBy(['enabled' => 'DESC', 'createdAt' => 'DESC'])]
    private Collection $regularSchedulerList;

    #[ORM\OneToMany(mappedBy: 'specialScheduler', targetEntity: SpecialScheduler::class, cascade: ['persist'])]
    #[ORM\OrderBy(['enabled' => 'DESC', 'createdAt' => 'DESC'])]
    private Collection $specialSchedulerList;

    #[ORM\OneToMany(mappedBy: 'vacationScheduler', targetEntity: VacationScheduler::class, cascade: ['persist'])]
    #[ORM\OrderBy(['enabled' => 'DESC', 'createdAt' => 'DESC'])]
    private Collection $vacationSchedulerList;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $enabled = true;

    #[ORM\Column(type: Types::STRING)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->regularSchedulerList = new ArrayCollection();
        $this->specialSchedulerList = new ArrayCollection();
        $this->vacationSchedulerList = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRegularSchedulerList(): Collection
    {
        return $this->regularSchedulerList;
    }

    public function addRegularSchedulerList(RegularScheduler $regularScheduler): self
    {
        $regularScheduler->setLocation($this);
        $this->regularSchedulerList->add($regularScheduler);

        return $this;
    }

    public function getSpecialSchedulerList(): Collection
    {
        return $this->specialSchedulerList;
    }

    public function addSpecialSchedulerList(SpecialScheduler $specialScheduler): self
    {
        $specialScheduler->setLocation($this);
        $this->specialSchedulerList->add($specialScheduler);

        return $this;
    }

    public function getVacationSchedulerList(): Collection
    {
        return $this->vacationSchedulerList;
    }

    public function addVacationSchedulerList(VacationScheduler $vacationScheduler): self
    {
        $vacationScheduler->setLocation($this);
        $this->vacationSchedulerList->add($vacationScheduler);

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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
