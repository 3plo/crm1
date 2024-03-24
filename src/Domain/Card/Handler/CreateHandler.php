<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:35
 */

namespace App\Domain\Card\Handler;

use App\Application\Barcode\Command\CreateCommand as BarcodeCreateCommand;
use App\Application\Barcode\Handler\CreateHandler as BarcodeCreateHandler;
use App\Application\Card\Command\CreateCommand;
use App\Domain\Card\Card;
use Doctrine\ORM\EntityManagerInterface;

class CreateHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BarcodeCreateHandler $barcodeCreateHandler,
    ) {
    }

    public function handle(CreateCommand $command): void
    {
        $product = $command->getProduct();
        $card = (new Card())
            ->setProduct($product)
            ->setValidFrom(new \DateTimeImmutable())
            ->setValidTill(
                (new \DateTimeImmutable(sprintf('+%s days', $product->getDurationDays())))
            )
            ->setType($product->getType())//TODO get from command or move to product or price
            ->setEnabled(true)
            ->setCountUsage(0)
            ->setMaxUsage($product->getCountUsage());

        $this->entityManager->persist($card);
        $this->entityManager->flush();

        $this->barcodeCreateHandler->handle(
            new BarcodeCreateCommand(
                $card,
            ),
        );
    }
}
