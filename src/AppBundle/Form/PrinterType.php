<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrinterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('batch', TextType::class)
            ->add('model')
            ->add('problemDescription', TextareaType::class)
            ->add('cartridge', TextareaType::class)
            ->add('serialNumber', TextType::class)
            ->add('diagnostic', TextareaType::class)
            ->add('invoiceDescription', TextareaType::class)
            ->add('warrantySticker', CheckboxType::class)
            ->add('counter', TextareaType::class)
            ->add('addPrice', NumberType::class)
            ->add('customerPrice', NumberType::class)
            ->add('addProtocol', TextType::class)
            ->add('comments', TextareaType::class)
            ->add('printerStatus', TextType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Printer'
        ));
    }
}
