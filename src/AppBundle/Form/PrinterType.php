<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('companyName')
            ->add('problemDescription', TextareaType::class)
            ->add('cartridge', TextareaType::class)
            ->add('serialNumber', TextType::class)
            ->add('diagnostic', TextareaType::class)
            ->add('invoiceDescription', TextareaType::class)
            ->add('warrantySticker', CheckboxType::class, array(
                'required' => false,
                'value' => 1,
            ))
            ->add('counter', TextareaType::class)
            ->add('printerStatus')
            ->add('addPrice', NumberType::class)
            ->add('customerPrice', NumberType::class)
            ->add('addProtocol', TextType::class)
            ->add('comments', TextareaType::class)
            ->add('image', FileType::class, [
                'label' => 'Image (Image file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [

                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
