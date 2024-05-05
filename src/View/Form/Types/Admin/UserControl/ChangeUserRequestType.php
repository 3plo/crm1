<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 21:58
 */

namespace App\View\Form\Types\Admin\UserControl;

use App\Domain\Location\Repository\LocationRepository;
use App\Domain\User\Enum\Action;
use App\Domain\User\Enum\Role;
use App\View\Form\Constraint\Location\LocationExist;
use App\View\Form\Constraint\User\UserExist;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Admin\UserControl\ChangeUserRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeUserRequestType extends AbstractRequestType
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {
    }

    #[\Override] public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userId = $options['data']['userId'] ?? '';
        $builder
            ->add(
                'userId',
                HiddenType::class,
                [
                    'label' => false,
                    'constraints' => [
                        new UserExist(),
                    ],
                    'attr' => ['value' => $userId],
                ],
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Email(),
                    ],
                ]
            )
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'First name',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'Last name',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'Password',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'role',
                ChoiceType::class,
                [
                    'label' => 'Role',
                    'attr' => ['class' => 'input-field'],
                    'choices' => Role::toArray(),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'accessList',
                ChoiceType::class,
                [
                    'label' => 'Access list',
                    'attr' => ['class' => 'input-field'],
                    'choices' => Action::toArray(),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'multiple' => true,
                ]
            )
            ->add(
                'locationAccessList',
                ChoiceType::class,
                [
                    'label' => 'Location access list',
                    'attr' => ['class' => 'input-field'],
                    'choices' => $this->prepareLocationList(),
                    'constraints' => [
                        new Assert\All(
                            [
                                new LocationExist(),
                                new Assert\NotBlank(),
                            ]
                        ),
                    ],
                    'multiple' => true,
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Save User',
                    'attr' => ['class' => 'btn'],
                ]
            )
        ;
    }

    #[\Override] public static function getRequestClass(): string
    {
        return ChangeUserRequest::class;
    }

    private function prepareLocationList(): array
    {
        $result = [];
        foreach ($this->locationRepository->findBy(['enabled' => true]) as $location) {
            $result[$location->getTitle()] = $location->getId();
        }

        return $result;
    }
}
