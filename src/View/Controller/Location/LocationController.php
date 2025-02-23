<?php
/**
 * Created by PhpStorm.
 * Date: 10.02.2024
 * Time: 0:24
 */

namespace App\View\Controller\Location;

use App\Application\Location\Builder\UserLocationListBuilder;
use App\Domain\Location\Enum\ScheduleType;
use App\Domain\Location\Location;
use App\Domain\Location\RegularScheduler;
use App\Domain\Location\Repository\LocationRepository;
use App\Domain\Location\Repository\RegularSchedulerRepository;
use App\Domain\Location\Repository\SpecialSchedulerRepository;
use App\Domain\Location\Repository\VacationSchedulerRepository;
use App\Domain\Location\SpecialScheduler;
use App\Domain\Location\VacationScheduler;
use App\Domain\User\Enum\Action;
use App\View\Access\Attribute\ActionAccess;
use App\View\Form\Types\Location\LocationCreateFormType;
use App\View\Form\Types\Location\LocationEditFormType;
use App\View\Request\Location\LocationCreateRequest;
use App\View\Request\Location\LocationEditRequest;
use App\View\Request\Location\ToggleLocationRequest;
use App\View\Request\Location\ToggleLocationScheduleRequest;
use App\View\RequestResolver\FormRequestResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class LocationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface           $entityManager,
        private readonly FormRequestResolver              $formRequestResolver,
        private readonly UserLocationListBuilder          $userLocationListBuilder,
        private readonly LocationRepository               $locationRepository,
        private readonly RegularSchedulerRepository       $regularSchedulerRepository,
        private readonly SpecialSchedulerRepository       $specialSchedulerRepository,
        private readonly VacationSchedulerRepository      $vacationSchedulerRepository,
    ) {
    }

    #[ActionAccess([Action::LocationList->value])]
    #[Route(path: '/location/list', name: 'location_list', methods: 'GET')]
    public function locationList(): Response
    {
        return $this->render('location/list.html.twig', [
            'locationList' => $this->userLocationListBuilder->build(),
        ]);
    }

    #[ActionAccess([Action::LocationCreate->value])]
    #[Route(path: '/location/create', name: 'location_create')]
    public function locationCreate(Request $request): Response
    {
        try {
            /** @var LocationCreateRequest $locationRequest */
            $locationRequest = $this->formRequestResolver->resolve($request, LocationCreateFormType::class);
            $response = new Response(null, 200);
            if (null !== $locationRequest) {
                $location = (new Location())
                    ->setTitle($locationRequest->getTitle())
                    ->setDescription($locationRequest->getDescription())
                    ->setEnabled($locationRequest->isEnabled());

                foreach ($locationRequest->getRegularSchedulerList() as $regularSchedulerDTO) {
                    $regularScheduler = (new RegularScheduler())//TODO move to factory
                    ->setEnabled(true)
                        ->setDayNumber($regularSchedulerDTO->getDayNumber())
                        ->setTimeFrom($regularSchedulerDTO->getTimeFrom())
                        ->setTimeTill($regularSchedulerDTO->getTimeTill())
                        ->setDateFrom($regularSchedulerDTO->getDateFrom())
                        ->setDateTill($regularSchedulerDTO->getDateTill());

                    $location->addRegularScheduler($regularScheduler);
                }

                foreach ($locationRequest->getVacationSchedulerList() as $vacationSchedulerDTO) {
                    $vacationScheduler = (new VacationScheduler())//TODO move to factory
//                        ->setEnabled(true)//TODO add to entity
                    ->setDayNumber($vacationSchedulerDTO->getDayNumber())
                        ->setTitle($vacationSchedulerDTO->getTitle())
                        ->setDateFrom($vacationSchedulerDTO->getDateFrom())
                        ->setDateTill($vacationSchedulerDTO->getDateTill());

                    $location->addVacationScheduler($vacationScheduler);
                }

                foreach ($locationRequest->getSpecialSchedulerList() as $specialSchedulerDTO) {
                    $specialScheduler = (new SpecialScheduler())//TODO move to factory
//                        ->setEnabled(true)//TODO add to entity
                    ->setTimeFrom($specialSchedulerDTO->getTimeFrom())
                        ->setTimeTill($specialSchedulerDTO->getTimeTill())
                        ->setDateFrom($specialSchedulerDTO->getDateFrom())
                        ->setDateTill($specialSchedulerDTO->getDateTill());

                    $location->addSpecialScheduler($specialScheduler);
                }

                $this->entityManager->persist($location);
                $this->entityManager->flush();

                $this->addFlash('success', 'Location created successfully!');
                return $this->redirectToRoute('location_list');
            }

        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
            $response = new Response(null, 422);
        }

        $form = $this->createForm(LocationCreateFormType::class);

        return $this->render('location/create.html.twig', [
            'form' => $form->createView(),
        ], $response);
    }

    #[ActionAccess([Action::LocationActivate->value])]
    #[Route(path: '/location/toggle', name: 'location_location_toggle', methods: 'POST')]
    public function toggleLocation(ToggleLocationRequest $request): JsonResponse
    {
        $location = $this->locationRepository->find($request->getLocationId());
        $location->setEnabled($request->isEnabled());

        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'enabled' => $location->isEnabled(),
        ]);
    }

    #[ActionAccess([Action::LocationCreate->value])]
    #[Route(path: '/location/edit/{locationId}', name: 'location_edit')]
    public function locationEdit(string $locationId, Request $request): Response
    {
        $location = null;

        try {
            $location = $this->locationRepository->find($locationId);

            /** @var LocationEditRequest $locationRequest */
            $locationRequest = $this->formRequestResolver->resolve($request, LocationEditFormType::class);
            $response = new Response(null, 200);
            if (null !== $locationRequest) {
                $location
                    ->setTitle($locationRequest->getTitle())
                    ->setDescription($locationRequest->getDescription())
                    ->setEnabled($locationRequest->isEnabled());

                foreach ($locationRequest->getRegularSchedulerList() as $regularSchedulerDTO) {
                    $regularScheduler = (new RegularScheduler())//TODO move to factory
                        ->setEnabled(true)
                        ->setDayNumber($regularSchedulerDTO->getDayNumber())
                        ->setTimeFrom($regularSchedulerDTO->getTimeFrom())
                        ->setTimeTill($regularSchedulerDTO->getTimeTill())
                        ->setDateFrom($regularSchedulerDTO->getDateFrom())
                        ->setDateTill($regularSchedulerDTO->getDateTill());

                    $location->addRegularScheduler($regularScheduler);
                }

                foreach ($locationRequest->getVacationSchedulerList() as $vacationSchedulerDTO) {
                    $vacationScheduler = (new VacationScheduler())//TODO move to factory
                        ->setEnabled(true)
                        ->setDayNumber($vacationSchedulerDTO->getDayNumber())
                        ->setTitle($vacationSchedulerDTO->getTitle())
                        ->setDateFrom($vacationSchedulerDTO->getDateFrom())
                        ->setDateTill($vacationSchedulerDTO->getDateTill());

                    $location->addVacationScheduler($vacationScheduler);
                }

                foreach ($locationRequest->getSpecialSchedulerList() as $specialSchedulerDTO) {
                    $specialScheduler = (new SpecialScheduler())//TODO move to factory
                        ->setEnabled(true)
                        ->setTimeFrom($specialSchedulerDTO->getTimeFrom())
                        ->setTimeTill($specialSchedulerDTO->getTimeTill())
                        ->setDateFrom($specialSchedulerDTO->getDateFrom())
                        ->setDateTill($specialSchedulerDTO->getDateTill());

                    $location->addSpecialScheduler($specialScheduler);
                }

                $this->entityManager->persist($location);
                $this->entityManager->flush();

                $this->addFlash('success', 'Location created successfully!');
                return $this->redirectToRoute('location_list');
            }

        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
            $response = new Response(null, 422);
        }

        $form = $this->createForm(LocationEditFormType::class);

        return $this->render('location/edit.html.twig', [
            'form' => $form->createView(),
            'location' => $location,
        ], $response);
    }

    #[ActionAccess([Action::LocationActivate->value])]
    #[Route(path: '/location/schedule/toggle', name: 'location_location_schedule_toggle', methods: 'POST')]
    public function toggleLocationSchedule(ToggleLocationScheduleRequest $request): JsonResponse
    {
        $repository = match ($request->getType()) {
            ScheduleType::Regular->value => $this->regularSchedulerRepository,
            ScheduleType::Special->value => $this->specialSchedulerRepository,
            ScheduleType::Vacation->value => $this->vacationSchedulerRepository,
        };
        $scheduler = $repository->find($request->getSchedulerId());
        $scheduler->setEnabled($request->isEnabled());

        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'enabled' => $scheduler->isEnabled(),
        ]);
    }
}
