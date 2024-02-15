<?php
/**
 * Created by PhpStorm.
 * Date: 10.02.2024
 * Time: 0:24
 */

namespace App\View\Controller\Location;

use App\Domain\Location\Location;
use App\Domain\Location\RegularScheduler;
use App\Domain\Location\Repository\LocationRepository;
use App\View\Form\Location\LocationFormType;
use App\View\Request\Location\LocationCreateRequest;
use App\View\RequestResolver\FormRequestResolver;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface    $serializer,
        private readonly FormRequestResolver    $formRequestResolver,
        private readonly LocationRepository     $locationRepository,
    ) {
    }

    #[Route(path: '/location/list', name: 'location_list', methods: 'GET')]
    public function locationList(): Response
    {
        return $this->render('location/list.html.twig', [
            'locations' => $this->locationRepository->findAll(),
        ]);
    }

    #[Route(path: '/location/create', name: 'location_create')]
    public function locationCreate(Request $request): Response
    {
        try {
            /** @var LocationCreateRequest $locationRequest */
            $locationRequest = $this->formRequestResolver->resolve($request, LocationFormType::class);
            if (null !== $locationRequest) {
                $location = (new Location())
                    ->setTitle($locationRequest->getTitle())
                    ->setDescription($locationRequest->getDescription())
                    ->setEnabled($locationRequest->isEnabled());

                $this->entityManager->persist($location);
                $this->entityManager->flush();

                $this->addFlash('success', 'Location created successfully!');
                return $this->redirectToRoute('location_list');
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }

        $location = new Location();
        $location->addRegularSchedulerList(new RegularScheduler());
        $form = $this->createForm(LocationFormType::class, $location);

        return $this->render('location/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
