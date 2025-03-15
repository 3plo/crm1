<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:35
 */

namespace App\Domain\Card\Handler;

use App\Application\Barcode\Command\CreateExternalCommand;
use App\Application\Barcode\Command\CreateGeneratedCommand as BarcodeCreateCommand;
use App\Application\Barcode\Handler\CreateHandler as BarcodeCreateHandler;
use App\Application\Card\Command\CreateCommand;
use App\Domain\Card\Card;
use App\Domain\User\User;
use App\Infrastructure\Provider\CardProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

readonly class CreateHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BarcodeCreateHandler   $barcodeCreateHandler,
        private CardProvider           $cardProvider,
        private Security               $security,
    ) {
    }

    public function handle(CreateCommand $command): void
    {
        $user = $this->security->getUser();
        if (false === $user instanceof User) {
            //TODO throw and handle exception
            $user = null;
        }

        $product = $command->getProduct();
        $card = (new Card())
            ->setProduct($product)
            ->setPrice($command->getPrice())
            ->setValidFrom(new \DateTimeImmutable())
            ->setValidTill(
                (new \DateTimeImmutable(sprintf('+%s days', $product->getDurationDays())))
            )
            ->setType($product->getType())//TODO get from command or move to product or price
            ->setEnabled(true)
            ->setCountUsage(0)
            ->setMaxUsage($product->getCountUsage())
            ->setCreatedBy($user);

        $this->entityManager->persist($card);
        $this->entityManager->flush();

        $this->cardProvider->setCard($card);

        if (null === $command->getBarcode()) {
            $this->barcodeCreateHandler->handleGenerated(
                new BarcodeCreateCommand(
                    $card,
                ),
            );

            return;
        }

        $this->barcodeCreateHandler->handleExternal(
            new CreateExternalCommand(
                $card,
                $command->getBarcode(),
            ),
        );
    }
}
