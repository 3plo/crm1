<?php
/**
 * Created by PhpStorm.
 * Date: 26.02.2024
 * Time: 21:06
 */

namespace App\Domain\Barcode\Handler;

use App\Domain\Barcode\Repository\BarcodeRepository;
use App\Domain\Barcode\Result\BarcodeHandleResult;
use App\Domain\Product\Repository\ProductRepository;
use Symfony\Bundle\SecurityBundle\Security;

class BarcodeHandler
{
    public function __construct(
        private readonly BarcodeRepository $barcodeRepository,
        private readonly ProductRepository $productRepository,
        private readonly Security $security,
    ) {
    }

    public function handle(
        string $barcode,
        string $productId,
    ): BarcodeHandleResult {
        $product = $this->productRepository->find($productId);
        $barcodeData = $this->barcodeRepository->findOneBy(['barcode' => $barcode]);

        if (null === $barcodeData) {
            return new BarcodeHandleResult(false, 'Barcode not found');
        }

        if ($product !== $barcodeData->getProduct()) {//TODO maybe change on location
            return new BarcodeHandleResult(false, 'No corresponding for current product');
        }

        return new BarcodeHandleResult(true, 'allow');
    }
}
