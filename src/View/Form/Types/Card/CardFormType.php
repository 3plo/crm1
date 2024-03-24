<?php
/**
 * Created by PhpStorm.
 * Date: 10.03.2024
 * Time: 14:14
 */

namespace App\View\Form\Types\Card;

use App\Domain\Product\Price;
use App\View\Form\Constraint\Product\ProductExist;
use App\View\Form\Types\AbstractRequestType;
use App\View\Request\Card\CreateRequest;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CardFormType extends AbstractRequestType
{
    #[\Override] public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $productId = $options['data']['productId'] ?? '';
        $builder
            ->add(
                'product',
                HiddenType::class,
                [
                    'label' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new ProductExist(),
                    ],
                    'attr' => ['value' => $productId],
                ],
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Create Card',
                    'attr' => ['class' => 'btn'],
                ]
            );

        if (true === array_key_exists('data', $options) && null !== $options['data'] && [] !== $options['data']) {
            $builder
                ->add(
                    'price',
                    ChoiceType::class,
                    [
                        'label' => 'Price',
                        'attr' => ['class' => 'input-field'],
                        'choices' => $this->preparePriceList($options['data']['priceList']),
                    ],
                );

            return;
        }
        $builder
            ->add(
                'price',
                TextType::class,//TODO validate if exist
                [
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ],
            );
    }

    #[\Override] public static function getRequestClass(): string
    {
        return CreateRequest::class;
    }

    /**
     * @param Price[] $priceList
     */
    private function preparePriceList(array $priceList): array
    {
        $result = [];
        foreach ($priceList as $price) {
            $result[$price->getTitle()] = $price->getId();
        }

        return $result;
    }
}
