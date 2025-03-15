<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:43
 */

namespace App\View\Controller\Barcode;


use App\Application\Barcode\Handler\BarcodeCheckHandler;
use App\Domain\Location\Repository\LocationRepository;
use App\Domain\User\Enum\Action;
use App\View\Access\Attribute\ActionAccess;
use App\View\Access\Checker\LocationChecker;
use App\View\Request\Barcode\FindRequest;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class BarcodeControlController extends AbstractController
{
    public function __construct(
        private readonly BarcodeCheckHandler $barcodeHandler,
        private readonly LocationChecker     $locationChecker,
        private readonly LocationRepository  $locationRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[ActionAccess([Action::EntranceControl->value])]
    #[Route(path: '/barcode/check/{locationId}', name: 'barcode_check', methods: 'GET')]
    public function check(string $locationId): Response
    {
        $this->locationChecker->checkAccessToAnyLocationList([$locationId]);

        return $this->render(
            'barcode/check.html.twig',
            [
                'location' => $this->locationRepository->find($locationId),
            ],
        );
    }

    #[ActionAccess([Action::EntranceControl->value, Action::Sell->value])]
    #[Route(path: '/barcode/find', name: 'barcode_find', methods: 'POST')]
    public function find(FindRequest $request): Response
    {
        $locationId = $request->getLocationId();
        $this->locationChecker->checkAccessToAnyLocationList([$locationId]);

        return new JsonResponse(
            data: $this->serializer->serialize(
                $this->barcodeHandler->handle($request->getBarcode(), $locationId),
                'json',
            ),
            json: true,
        );
    }
}
