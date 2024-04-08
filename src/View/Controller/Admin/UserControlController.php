<?php
/**
 * Created by PhpStorm.
 * Date: 03.04.2024
 * Time: 20:57
 */

namespace App\View\Controller\Admin;

use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\View\Request\Admin\UserControl\ChangeUserRequest;
use App\View\Request\Admin\UserControl\ToggleUserRequest;
use App\View\RequestResolver\FormRequestResolver;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserControlController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface    $serializer,
        private readonly FormRequestResolver    $formRequestResolver,
        private readonly UserRepository         $userRepository,
    )
    {
    }

    public function userList(): Response
    {
        return $this->render('product/list.html.twig', [
            'userList' => $this->userRepository->findAll(),
        ]);
    }

    public function changeUser(ChangeUserRequest $request): Response
    {

        try {
            /** @var ProductCreateRequest $productRequest */
            $productRequest = $this->formRequestResolver->resolve($request, ProductCreateRequestType::class);
            if (null !== $productRequest) {
                $product = (new User())
                    ->setTitle($productRequest->getTitle())
                    ->setDescription($productRequest->getDescription())
                    ->setType(Type::from($productRequest->getType()))
                    ->setCountUsage($productRequest->getCountUsage())
                    ->setDurationDays($productRequest->getDurationDays())
                    ->setEnabled(true);

                foreach ($productRequest->getLocationList() as $locationId) {
                    $product->addLocation($this->locationRepository->find($locationId));
                }

                $this->entityManager->persist($product);
                $this->entityManager->flush();

                $this->addFlash('success', 'Product created successfully!');
                return $this->redirectToRoute('product_list');
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }
        $form = $this->createForm(ProductCreateRequestType::class);

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function toggleUser(ToggleUserRequest $request): JsonResponse
    {
        $user = $this->userRepository->find($request->getUserId());
        $user->setEnabled($request->isEnabled());

        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'enabled' => $user->isEnabled(),
        ]);
    }
}
