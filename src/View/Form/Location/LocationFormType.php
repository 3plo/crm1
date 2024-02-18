<?php
/**
 * Created by PhpStorm.
 * Date: 12.02.2024
 * Time: 21:47
 */

namespace App\View\Form\Location;

use App\View\Form\AbstractRequestType;
use App\View\Form\Location\Detail\RegularSchedulerType;
use App\View\Form\Location\Detail\SpecialSchedulerType;
use App\View\Form\Location\Detail\VacationSchedulerType;
use App\View\Request\Location\LocationCreateRequest;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LocationFormType extends AbstractRequestType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'Enabled',
                    'required' => false,
                ]
            )
            ->add(
                'regularSchedulerList',
                CollectionType::class,
                [
                    'entry_type' => RegularSchedulerType::class,
                    'label' => false,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'prototype_name' => '__name__',
                ]
            )
            ->add(
                'vacationSchedulerList',
                CollectionType::class,
                [
                    'entry_type' => VacationSchedulerType::class,
                    'label' => false,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'prototype_name' => '__name__',
                ]
            )
            ->add(
                'specialSchedulerList',
                CollectionType::class,
                [
                    'entry_type' => SpecialSchedulerType::class,
                    'label' => false,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'by_reference' => false,
                    'prototype' => true,
                    'prototype_name' => '__name__',
                ]
            )
        ;
    }

    #[\Override] public static function getRequestClass(): string
    {
        return LocationCreateRequest::class;
    }
}