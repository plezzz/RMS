<?php

namespace AppBundle\Form;

use AppBundle\Entity\Printer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('technician')
            ->add('problemDescription', TextareaType::class)
            ->add('cartridge', TextareaType::class)
            ->add('printerStatus')
            ->add('serialNumber', TextType::class)
            ->add('diagnostic', TextareaType::class)
            ->add('invoiceDescription', TextareaType::class)
            ->add('warrantySticker', CheckboxType::class, array(
                'required' => false,
                'value' => 1,
            ))
            ->add('showComment', CheckboxType::class, array(
                'required' => false,
                'value' => 1,
            ))
            ->add('counter', TextareaType::class)
            ->add('addPrice', NumberType::class)
            ->add('customerPrice', NumberType::class)
            ->add('addProtocol', TextType::class)
            ->add('comments', TextareaType::class)
            ->add('image', FileType::class, [
                'label' => 'Image (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Printer::class]);
    }
}
