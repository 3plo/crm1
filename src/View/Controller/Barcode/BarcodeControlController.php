<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:43
 */

namespace App\View\Controller\Barcode;


use App\Application\Barcode\Handler\BarcodeHandler;
use App\Domain\Location\Repository\LocationRepository;
use App\View\Request\Barcode\FindRequest;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BarcodeControlController extends AbstractController
{
    public function __construct(
        private BarcodeHandler $barcodeHandler,
        private LocationRepository $locationRepository,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route(path: '/barcode/check/{locationId}', name: 'barcode_check', methods: 'GET')]
    public function check(string $locationId): Response
    {
        return $this->render(
            'barcode/check.html.twig',
            [
                'location' => $this->locationRepository->find($locationId),//TODO check is exist and has access
            ],
        );
    }

    #[Route(path: '/barcode/find', name: 'barcode_find', methods: 'POST')]
    public function find(FindRequest $request): Response
    {
        return new JsonResponse(
            data: $this->serializer->serialize(
                $this->barcodeHandler->handle($request->getBarcode(), $request->getLocationId()),
                'json',
            ),
            json: true,
        );
    }
}
