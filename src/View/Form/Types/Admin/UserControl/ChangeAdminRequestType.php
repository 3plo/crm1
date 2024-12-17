<?php
/**
 * Created by PhpStorm.
 * Date: 08.04.2024
 * Time: 21:58
 */

namespace App\View\Form\Types\Admin\UserControl;

use App\View\Form\Constraint\User\UserExist;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Admin\UserControl\ChangeAdminRequest;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangeAdminRequestType extends AbstractRequestType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
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
                'save',
                SubmitType::class,
                [
                    'label' => $this->translator->trans('save_admin_button'),
                    'attr' => ['class' => 'button'],
                ]
            )
        ;
    }

    #[\Override] public static function getRequestClass(): string
    {
        return ChangeAdminRequest::class;
    }
}
