<?php
/**
 * Created by PhpStorm.
 * Date: 16.03.2024
 * Time: 10:15
 */

namespace App\View\Form\Types\Report\SaleReport;

use App\Application\Product\Builder\UserProductListBuilder;
use App\Application\User\Builder\UserListBuilder;
use App\View\Form\Constraint\Product\ProductExist;
use App\View\Form\Constraint\User\UserExist;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Report\SaleReport\GeneralReportRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

class GeneralReportRequestType extends AbstractRequestType
{
    public function __construct(
        private readonly UserProductListBuilder  $userProductListBuilder,
        private readonly UserListBuilder         $userListBuilder,
        private readonly TranslatorInterface     $translator,
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
                    'label' => $this->translator->trans('general_traffic_report_date_from_label'),
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
                    'label' => $this->translator->trans('general_traffic_report_date_till_label'),
                    'attr' => ['class' => 'input-field'],
                    'required' => true,
                    'widget' => 'single_text',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ],
            )
            ->add(
                'product',
                ChoiceType::class,
                [
                    'label' => $this->translator->trans('general_traffic_report_product_label'),
                    'attr' => ['class' => 'input-field'],
                    'choices' => $this->prepareProductList(),
                    'placeholder' => '---',
                    'required' => false,
                    'empty_data' => null,
                    'constraints' => [
                        new ProductExist(),
                    ],
                ],
            )
            ->add(
                'user',
                ChoiceType::class,
                [
                    'label' => $this->translator->trans('general_sale_report_user_label'),
                    'attr' => ['class' => 'input-field'],
                    'choices' => $this->prepareUserList(),
                    'placeholder' => '---',
                    'required' => false,
                    'empty_data' => null,
                    'constraints' => [
                        new UserExist(),
                    ],
                ],
            );
    }

    #[\Override] public static function getRequestClass(): string
    {
        return GeneralReportRequest::class;
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

    private function prepareUserList(): array
    {
        $result = [];
        foreach ($this->userListBuilder->build() as $user) {
            $result[sprintf('%s %s', $user->getFirstName(), $user->getLastName())] = $user->getId();
        }

        return $result;
    }
}
