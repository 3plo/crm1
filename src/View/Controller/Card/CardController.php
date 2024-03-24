<?php
/**
 * Created by PhpStorm.
 * Date: 02.03.2024
 * Time: 22:11
 */

namespace App\View\Controller\Card;

use App\Application\Card\Command\CreateCommand;
use App\Domain\Card\Handler\CreateHandler;
use App\Domain\Product\Repository\ProductRepository;
use App\View\Form\Types\Card\CardFormType;
use App\View\Request\Card\CreateRequest;
use App\View\RequestResolver\FormRequestResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    public function __construct(
        private readonly CreateHandler       $createHandler,
        private readonly FormRequestResolver $formRequestResolver,
        private readonly ProductRepository   $productRepository,
    ) {
    }

    #[Route(path: '/card/create/{productId}', name: 'card_create')]
    public function create(string $productId, Request $request): Response
    {
        try {
            /** @var CreateRequest $cardRequest */

            $cardRequest = $this->formRequestResolver->resolve($request, CardFormType::class);
            if (null !== $cardRequest) {
                $this->createHandler->handle(
                    new CreateCommand(
                        $this->productRepository->find($productId),
                        $cardRequest->getPrice(),
                    ),
                );

                return $this->redirectToRoute('product_list');
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
}
