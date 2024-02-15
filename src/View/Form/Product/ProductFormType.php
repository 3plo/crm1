<?php
/**
 * Created by PhpStorm.
 * Date: 31.01.2024
 * Time: 23:22
 */

namespace App\View\Form\Product;

use App\View\Form\AbstractRequestType;
use App\View\Request\Product\ProductCreateRequest;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProductFormType extends AbstractRequestType
{
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
}
