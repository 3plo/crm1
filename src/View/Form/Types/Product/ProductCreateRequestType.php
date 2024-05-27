<?php
/**
 * Created by PhpStorm.
 * Date: 31.01.2024
 * Time: 23:22
 */

namespace App\View\Form\Types\Product;

use App\Application\Location\Builder\UserLocationListBuilder;
use App\Domain\Card\Enum\Type;
use App\View\Form\Constraint\Location\LocationExist;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Product\ProductCreateRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductCreateRequestType extends AbstractRequestType
{
    public function __construct(
        private readonly UserLocationListBuilder $userLocationListBuilder,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[\Override] public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'durationDays',
                IntegerType::class,
                [
                    'label' => $this->translator->trans('product_duration_create_label'),
                    'attr' => ['min' => 1],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Positive(),
                    ],
                ]
            )
            ->add(
                'countUsage',
                IntegerType::class,
                [
                    'label' => $this->translator->trans('product_max_count_usage_create_label'),
                    'attr' => ['min' => 1],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Positive(),
                    ],
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    'label' => $this->translator->trans('product_type_create_label'),
                    'attr' => ['class' => 'input-field'],
                    'choices' => Type::viewCases(),
                ],
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label' => $this->translator->trans('product_title_create_label'),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => $this->translator->trans('product_description_create_label'),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'locationList',
                ChoiceType::class,
                [
                    'label' => $this->translator->trans('product_list_create_label'),
                    'attr' => ['class' => 'input-field'],
                    'choices' => $this->prepareLocationList(),
                    'constraints' => [
                        new Assert\NotBlank(),
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
                    'label' => $this->translator->trans('product_form_create_button'),
                    'attr' => ['class' => 'button'],
                ]
            )
        ;
    }

    #[\Override] public static function getRequestClass(): string
    {
        return ProductCreateRequest::class;
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
