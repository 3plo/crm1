<?php
/**
 * Created by PhpStorm.
 * Date: 31.01.2024
 * Time: 23:04
 */

namespace App\View\Controller\Product;

use App\Domain\Product\Price;
use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepository;
use App\View\Form\Product\ProductFormType;
use App\View\Request\Product\ProductCreateRequest;
use App\View\Request\Product\ProductPriceCreateRequest;
use App\View\Request\Product\ProductPriceToggleRequest;
use App\View\RequestResolver\FormRequestResolver;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface    $serializer,
        private readonly FormRequestResolver    $formRequestResolver,
        private readonly ProductRepository      $productRepository,
    ) {
    }

    #[Route(path: '/product/list', name: 'product_list', methods: 'GET')]
    public function productList(): Response
    {
        return $this->render('product/list.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route(path: '/product/create', name: 'product_create')]
    public function productCreate(Request $request): Response
    {
        try {
            /** @var ProductCreateRequest $productRequest */
            $productRequest = $this->formRequestResolver->resolve($request, ProductFormType::class);
            if (null !== $productRequest) {
                $product = (new Product())
                    ->setTitle($productRequest->getTitle())
                    ->setDescription($productRequest->getDescription())
                    ->setDurationDays($productRequest->getDurationDays())
                    ->setEnabled(true);

                $this->entityManager->persist($product);
                $this->entityManager->flush();

                $this->addFlash('success', 'Product created successfully!');
                return $this->redirectToRoute('product_list');
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }
        $form = $this->createForm(ProductFormType::class);

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/product/price/toggle', name: 'product_price_toggle', methods: 'POST')]
    public function togglePrice(ProductPriceToggleRequest $request): JsonResponse
    {
        $product = $this->productRepository->find($request->getProductId());
        $price = $product->getPriceById($request->getPriceId());

        $price->setEnabled($request->isEnabled());

        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'enabled' => $price->isEnabled(),
        ]);
    }

    #[Route(path: '/product/price/create', name: 'product_price_create', methods: 'POST')]
    public function createPrice(ProductPriceCreateRequest $request): JsonResponse
    {
        $product = $this->productRepository->find($request->getProductId());
        $price = (new Price())
            ->setTitle($request->getTitle())
            ->setEnabled($request->isEnabled())
            ->setAmountInUAH($request->getAmountInUAH());
        $product->addPrice($price);

        $this->entityManager->persist($price);
        $this->entityManager->flush();

        return new JsonResponse(
            $this->serializer->serialize(
                [
                    'success' => true,
                    'price' => $price,
                ],
                'json',
            )
        );
    }
}
