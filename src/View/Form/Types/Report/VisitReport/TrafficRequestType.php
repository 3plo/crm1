<?php
/**
 * Created by PhpStorm.
 * Date: 16.03.2024
 * Time: 10:15
 */

namespace App\View\Form\Types\Report\VisitReport;

use App\Application\Product\Builder\UserProductListBuilder;
use App\View\Form\Constraint\Location\LocationExist;
use App\View\Form\Constraint\Product\ProductExist;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Report\VisitReport\TrafficRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

class TrafficRequestType extends AbstractRequestType
{
    public function __construct(
        private readonly UserProductListBuilder $userProductListBuilder,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[\Override] public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['method' => 'GET']);
    }

    #[\Override] public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locationId = $options['data']['locationId'] ?? '';
        $builder
            ->add(
                'dateFrom',
                DateType::class,
                [
                    'label' => $this->translator->trans('location_traffic_report_date_from_label'),
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
                    'label' => $this->translator->trans('location_traffic_report_date_till_label'),
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
                HiddenType::class,
                [
                    'label' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new LocationExist(),
                    ],
                    'attr' => ['value' => $locationId],
                ],
            )
            ->add(
                'product',
                ChoiceType::class,
                [
                    'label' => $this->translator->trans('location_traffic_report_product_label'),
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
        return TrafficRequest::class;
    }

    private function prepareProductList(): array
    {
        $result = [];
        foreach ($this->userProductListBuilder->build() as $product) {
            if (false === $product->isEnabled()) {
                continue;
            }

            $result[$product->getTitle()] = $product->getId();
        }

        return $result;
    }
}
