<?php
/**
 * Created by PhpStorm.
 * Date: 12.02.2024
 * Time: 21:48
 */

namespace App\View\Request\Location;

use App\View\Request\FormRequestInterface;
use App\View\Request\Location\DTO\RegularSchedulerDTO;
use App\View\Request\Location\DTO\SpecialSchedulerDTO;
use App\View\Request\Location\DTO\VacationSchedulerDTO;
use JMS\Serializer\Annotation as JMS;

class LocationCreateRequest implements FormRequestInterface
{
    private bool $enabled = false;

    private string $title;

    private string $description;

    /**
     * @var RegularSchedulerDTO[]
     */
    #[JMS\SerializedName('regularSchedulerList')]
    #[JMS\Type('array<' . RegularSchedulerDTO::class . '>')]
    private array $regularSchedulerList = [];

    /**
     * @var VacationSchedulerDTO[]
     */
    #[JMS\SerializedName('vacationSchedulerList')]
    #[JMS\Type('array<' . VacationSchedulerDTO::class . '>')]
    private array $vacationSchedulerList = [];

    /**
     * @var SpecialSchedulerDTO[]
     */
    #[JMS\SerializedName('specialSchedulerList')]
    #[JMS\Type('array<' . SpecialSchedulerDTO::class . '>')]
    private array $specialSchedulerList = [];

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRegularSchedulerList(): array
    {
        return $this->regularSchedulerList;
    }

    public function getVacationSchedulerList(): array
    {
        return $this->vacationSchedulerList;
    }

    public function getSpecialSchedulerList(): array
    {
        return $this->specialSchedulerList;
    }
}
