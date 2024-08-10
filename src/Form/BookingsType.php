<?php

namespace App\Form;

use App\Entity\Bookings;
use App\Entity\Services;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class BookingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
        $builder
            ->add('username', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('service', EntityType::class, [
                'class' => Services::class,
                'choice_label' => 'name',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',  // HTML5 date picker
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),  // Disable past dates
                ],
            ])
            ->add('time', TimeType::class)
            ->add('email', EmailType::class)
            ->add('duration', IntegerType::class)
            ->add('TotalPrice', IntegerType::class, [
                'mapped' => false, // Do not map this field to the entity
                'attr' => ['readonly' => true],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bookings::class,
        ]);
    }
}
