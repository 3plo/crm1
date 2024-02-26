<?php
/**
 * Created by PhpStorm.
 * Date: 13.02.2024
 * Time: 21:52
 */

namespace App\View\Form\Types\Location\Detail;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialSchedulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'timeFrom',
                TimeType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'special-scheduler-item-input input-field'],
                    'widget' => 'single_text',
                ],
            )
            ->add(
                'timeTill',
                TimeType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'special-scheduler-item-input input-field'],
                    'widget' => 'single_text',
                ],
            )
            ->add(
                'dateFrom',
                DateType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'special-scheduler-item-input input-field'],
                    'widget' => 'single_text',
                ],
            )
            ->add(
                'dateTill',
                DateType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'special-scheduler-item-input input-field'],
                    'widget' => 'single_text',
                    'required' => false,
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'attr' => ['class' => 'special-scheduler-item-row'],
            ],
        );
    }
}
