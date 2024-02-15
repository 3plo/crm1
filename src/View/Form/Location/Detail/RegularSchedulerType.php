<?php
/**
 * Created by PhpStorm.
 * Date: 13.02.2024
 * Time: 21:52
 */

namespace App\View\Form\Location\Detail;

use App\Domain\Location\RegularScheduler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegularSchedulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dayNumber', ChoiceType::class, [
                'label' => false,
                'attr' => ['class' => 'regular-scheduler-item-input input-field'],
                'choices' => [
                    'Monday' => 'monday',
                    'Tuesday' => 'tuesday',
                    'Wednesday' => 'wednesday',
                    'Thursday' => 'thursday',
                    'Friday' => 'friday',
                    'Saturday' => 'saturday',
                    'Sunday' => 'sunday',
                ],
            ])
            ->add('timeFrom', TimeType::class, [
                'label' => false,
                'attr' => ['class' => 'regular-scheduler-item-input input-field'],
                'widget' => 'single_text',
            ])
            ->add('timeTill', TimeType::class, [
                'label' => false,
                'attr' => ['class' => 'regular-scheduler-item-input input-field'],
                'widget' => 'single_text',
            ])
            ->add('dateFrom', DateType::class, [
                'label' => false,
                'attr' => ['class' => 'regular-scheduler-item-input input-field'],
                'widget' => 'single_text',
            ])
            ->add('dateTill', DateType::class, [
                'label' => false,
                'attr' => ['class' => 'regular-scheduler-item-input input-field'],
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegularScheduler::class,
        ]);
    }
}
