<?php
/**
 * Created by PhpStorm.
 * Date: 16.03.2024
 * Time: 10:15
 */

namespace App\View\Form\Types\Report\VisitReport;

use App\Domain\Location\Repository\LocationRepository;
use App\Domain\Product\Repository\ProductRepository;
use App\View\Form\Constraint\Location\LocationExist;
use App\View\Form\Constraint\Product\ProductExist;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Report\VisitReport\GeneralRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class GeneralRequestType extends AbstractRequestType
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly ProductRepository $productRepository,
    ) {
    }

    #[\Override] public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['method' => 'GET']);
    }

    #[\Override] public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'dateFrom',
                DateType::class,
                [
                    'label' => 'Date from',
                    'attr' => ['class' => 'input-field'],
                    'required' => true,
                    'widget' => 'single_text',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ],
            )
            ->add(
                'dateTill',
                DateType::class,
                [
                    'label' => 'Date till',
                    'attr' => ['class' => 'input-field'],
                    'required' => true,
                    'widget' => 'single_text',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ],
            )
            ->add(
                'location',
                ChoiceType::class,
                [
                    'label' => 'Location',
                    'attr' => ['class' => 'input-field'],
                    'choices' => $this->prepareLocationList(),
                    'placeholder' => '---',
                    'required' => false,
                    'empty_data' => null,
                    'constraints' => [
                        new LocationExist(),
                    ],
                ],
            )
            ->add(
                'product',
                ChoiceType::class,
                [
                    'label' => 'Product',
                    'attr' => ['class' => 'input-field'],
                    'choices' => $this->prepareProductList(),
                    'placeholder' => '---',
                    'required' => false,
                    'empty_data' => null,
                    'constraints' => [
                        new ProductExist(),
                    ],
                ],
            );
    }

    #[\Override] public static function getRequestClass(): string
    {
        return GeneralRequest::class;
    }

    private function prepareLocationList(): array
    {
        $result = [];
        foreach ($this->locationRepository->findBy(['enabled' => true]) as $location) {
            $result[$location->getTitle()] = $location->getId();
        }

        return $result;
    }

    private function prepareProductList(): array
    {
        $result = [];
        foreach ($this->productRepository->findBy(['enabled' => true]) as $product) {
            $result[$product->getTitle()] = $product->getId();
        }

        return $result;
    }
}
