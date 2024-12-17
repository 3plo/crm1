<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 21:56
 */

namespace App\Application\Barcode\Handler;

use App\Application\Barcode\Command\CreateCommand;
use App\Domain\Barcode\Barcode;
use App\Domain\Barcode\Repository\BarcodeRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreateHandler
{
    public function __construct (
        private EntityManagerInterface $entityManager,
        private BarcodeRepository      $barcodeRepository,
    ) {
    }

    public function handle(CreateCommand $command): void
    {
        do {
            $barcode = $this->generateBarcodeEan13((string) rand(0, 10000));
        } while (null !== $this->barcodeRepository->findOneBy(['barcode' => $barcode]));

        $barcodeEntity = (new Barcode())
            ->setBarcode($barcode)
            ->setEnabled(true)
            ->setCard($command->getCard())
            ->setProduct($command->getCard()->getProduct());

        $this->entityManager->persist($barcodeEntity);
        $this->entityManager->flush();
    }

    private function generateBarcodeEan13(string $number): string
    {
        $code = '200' . str_pad($number, 9, '0');
        $weightFlag = true;
        $sum = 0;
        for ($i = strlen($code) - 1; $i >= 0; $i--)
        {
            $sum += (int)$code[$i] * ($weightFlag?3:1);
            $weightFlag = !$weightFlag;
        }
        $code .= (10 - ($sum % 10)) % 10;
        return $code;
    }
}
