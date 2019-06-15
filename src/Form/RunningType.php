<?php

namespace App\Form;

use App\Entity\Running;
use App\Entity\TypeRunning;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class RunningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', DateTimeType::class, [
                'label' => 'Date to start'
            ])
            ->add('duration', TimeType::class, [
                'label' => 'Duration (Hours : Minutes)'
            ])
            ->add('distance', NumberType::class, [
                'label' => 'Distance (Km)'
            ])
            ->add('comment')
            ->add('type', EntityType::class, [
                'class' => TypeRunning::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Running::class,
        ]);
    }
}
