<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:43
 */

namespace App\View\Controller\Barcode;


use App\Domain\Barcode\Handler\BarcodeHandler;
use App\View\Request\Barcode\FindRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BarcodeControlController extends AbstractController
{
    public function __construct(
        private BarcodeHandler $barcodeHandler,
    ) {
    }

    #[Route(path: '/barcode/check', name: 'barcode_check', methods: 'GET')]
    public function check(): Response
    {
        return $this->render('barcode/check.html.twig');
    }

    #[Route(path: '/barcode/find', name: 'barcode_find', methods: 'POST')]
    public function find(FindRequest $request): Response
    {
        return new JsonResponse(
            $this->barcodeHandler->handle($request->getBarcode(), $request->getLocationId()),
        );
    }
}
