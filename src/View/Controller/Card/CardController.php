<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:11
 */

namespace App\View\Controller\Card;

use App\Application\Card\Command\CreateCommand;
use App\Domain\Barcode\Repository\BarcodeRepository;
use App\Domain\Card\Handler\CreateHandler;
use App\Domain\Card\Repository\CardRepository;
use App\Domain\Product\Repository\PriceRepository;
use App\Domain\Product\Repository\ProductRepository;
use App\Domain\User\Enum\Action;
use App\Infrastructure\Provider\CardProvider;
use App\View\Access\Attribute\ActionAccess;
use App\View\Form\Types\Card\CardFormType;
use App\View\Request\Card\CreateRequest;
use App\View\RequestResolver\FormRequestResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TomasVotruba\BarcodeBundle\Base1DBarcode;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CardController extends AbstractController
{
    private readonly Base1DBarcode $base1DBarcode;//TODO move to service wrapper

    public function __construct(
        private readonly CreateHandler       $createHandler,
        private readonly FormRequestResolver $formRequestResolver,
        private readonly ProductRepository   $productRepository,
        private readonly PriceRepository     $priceRepository,
        private readonly CardRepository      $cardRepository,
        private readonly BarcodeRepository   $barcodeRepository,
        private readonly CardProvider        $cardProvider,
    ) {
        $this->base1DBarcode = new Base1DBarcode();
    }

    #[ActionAccess([Action::Sell->value])]
    #[Route(path: '/card/create/{productId}', name: 'card_create')]
    public function create(string $productId, Request $request): Response
    {//TODO check access to product
        try {
            /** @var CreateRequest $cardRequest */
            $cardRequest = $this->formRequestResolver->resolve($request, CardFormType::class);
            if (null !== $cardRequest) {
                $this->createHandler->handle(
                    new CreateCommand(
                        $this->productRepository->find($productId),
                        $this->priceRepository->find($cardRequest->getPrice()),
                    ),
                );

                return $this->redirectToRoute('card_print_view', ['cardId' => $this->cardProvider->getCard()->getId()]);
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }
        $product = $this->productRepository->find($productId);//TODO handle product not exist
        $form = $this->createForm(
            CardFormType::class,
            [
                'priceList' => $product->getActivePriceList()->toArray(),
                'productId' => $productId,
            ],
        );

        return $this->render('card/create.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[ActionAccess([Action::Sell->value])]
    #[Route(path: '/card/print/view/{cardId}', name: 'card_print_view')]
    public function viewPrint(string $cardId): Response
    {
        $card = $this->cardRepository->find($cardId);
        $barcode = $this->barcodeRepository->findOneBy(['card' => $card, 'enabled' => true]);
        $barcodeValue = $barcode->getBarcode();

        return $this->render('card/print_view.html.twig', [
            'barcode' => [
                'svg' => $this->base1DBarcode->getBarcodeSVGcode($barcodeValue, 'EAN13', 2, 45),
                'value' => $barcodeValue,
            ],
            'card' => $card,
        ]);
    }
}
