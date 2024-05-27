<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 21:58
 */

namespace App\View\Form\Types\Admin\UserControl;

use App\Application\Location\Builder\UserLocationListBuilder;
use App\Domain\Location\Repository\LocationRepository;
use App\Domain\User\Enum\Action;
use App\Domain\User\Enum\Role;
use App\Infrastructure\Services\TranslateService;
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
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangeUserRequestType extends AbstractRequestType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly TranslateService $translateService,
        private readonly UserLocationListBuilder $userLocationListBuilder,
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
                    'label' => $this->translator->trans('email_label'),
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
                    'label' => $this->translator->trans('first_name_label'),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => $this->translator->trans('last_name_label'),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => $this->translator->trans('password_label'),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'role',
                ChoiceType::class,
                [
                    'label' => $this->translator->trans('role_label'),
                    'attr' => ['class' => 'input-field'],
                    'choices' => array_flip($this->translateService->translateList(Role::toArray(), 'role_', '_option')),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'accessList',
                ChoiceType::class,
                [
                    'label' => $this->translator->trans('access_list_label'),
                    'attr' => ['class' => 'input-field'],
                    'choices' => array_flip($this->translateService->translateList(Action::toArray(), 'action_', '_option')),
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
                    'label' => $this->translator->trans('location_access_list_label'),
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
                    'label' => $this->translator->trans('save_user_button'),
                    'attr' => ['class' => 'button'],
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
        foreach ($this->userLocationListBuilder->build() as $location) {
            if (false === $location->isEnabled()) {
                continue;
            }

            $result[$location->getTitle()] = $location->getId();
        }

        return $result;
    }
}
