<?php
/**
 * Created by PhpStorm.
 * Date: 31.01.2024
 * Time: 23:22
 */

namespace App\View\Form\Types\Product;

use App\Domain\Card\Enum\Type;
use App\Domain\Location\Repository\LocationRepository;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Product\ProductCreateRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProductFormType extends AbstractRequestType
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'durationDays',
                IntegerType::class,
                [
                    'label' => 'Duration (in days)',
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
                    'label' => 'Max count usage',
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
                    'label' => 'Type',
                    'attr' => ['class' => 'input-field'],
                    'choices' => Type::viewCases(),
                ],
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Title',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'locationList',
                ChoiceType::class,
                [
                    'label' => 'Location list',
                    'attr' => ['class' => 'input-field'],
                    'choices' => $this->prepareLocationList(),
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'multiple' => true,
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Create Product',
                    'attr' => ['class' => 'btn'],
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
        foreach ($this->locationRepository->findBy(['enabled' => true]) as $location) {
            $result[$location->getTitle()] = $location->getId();
        }

        return $result;
    }
}
