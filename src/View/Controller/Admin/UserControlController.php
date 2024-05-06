<?php
/**
 * Created by PhpStorm.
 * Date: 03.04.2024
 * Time: 20:57
 */

namespace App\View\Controller\Admin;

use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\View\Access\Attribute\ActionAccess;
use App\View\Form\Types\Admin\UserControl\ChangeUserRequestType;
use App\View\Request\Admin\UserControl\ChangeUserRequest;
use App\View\Request\Admin\UserControl\ToggleUserRequest;
use App\View\RequestResolver\FormRequestResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserControlController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly FormRequestResolver         $formRequestResolver,
        private readonly UserRepository              $userRepository,
    ) {
    }

    #[ActionAccess]
    #[Route(path: '/user/control/list', name: 'user_control_user_list', methods: 'GET')]
    public function userList(): Response
    {
        return $this->render('admin/user/control/list.html.twig', [
            'userList' => $this->userRepository->findAll(),
        ]);
    }

    #[ActionAccess]
    #[Route(path: '/user/control/create', name: 'user_control_user_create')]
    public function changeUser(Request $request): Response
    {
        try {
            /** @var ChangeUserRequest $userRequest */
            $userRequest = $this->formRequestResolver->resolve($request, ChangeUserRequestType::class);
            if (null !== $userRequest) {
                $user = (new User())
                    ->setEmail($userRequest->getEmail())
                    ->setFirstName($userRequest->getFirstName())
                    ->setLastName($userRequest->getLastName())
                    ->setRoles([$userRequest->getRole()])
                    ->setAccessList($userRequest->getAccessList())
                    ->setLocationAccessList($userRequest->getLocationAccessList())
                    ->setEnabled(true)
                    ->setIsVerified(true);
                $user
                    ->setPassword(
                        $this->userPasswordHasher->hashPassword(
                            $user,
                            $userRequest->getPassword(),
                        )
                    );

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'User change successfully!');
                return $this->redirectToRoute('user_control_user_list');
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }
        $form = $this->createForm(ChangeUserRequestType::class);

        return $this->render('admin/user/control/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[ActionAccess]
    #[Route(path: '/user/control/toggle', name: 'user_control_user_toggle', methods: 'POST')]
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
