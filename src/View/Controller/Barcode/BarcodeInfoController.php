<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:44
 */

namespace App\View\Controller\Barcode;

use App\Application\Barcode\Handler\BarcodeInfoHandler;
use App\View\Request\Barcode\InfoRequest;
use App\View\Response\Formator\BarcodeInfoFormator;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BarcodeInfoController extends AbstractController
{
    public function __construct(
        private readonly BarcodeInfoHandler $barcodeHandler,
        private readonly SerializerInterface $serializer,
        private readonly BarcodeInfoFormator $barcodeInfoFormator,
    ) {
    }

    #[Route(path: '/barcode/info/page', name: 'barcode_info_page', methods: 'GET')]
    public function infoPage(): Response
    {
        return $this->render('barcode/info.html.twig');
    }
    #[Route(path: '/barcode/info/find', name: 'barcode_info_find', methods: 'POST')]
    public function find(InfoRequest $request): Response
    {
        return new JsonResponse(
            data: $this->serializer->serialize(
                $this->barcodeInfoFormator->formatBarcode(
                    $this->barcodeHandler->handle($request->getBarcode()),
                ),
                'json',
            ),
            json: true,
        );
    }
}
