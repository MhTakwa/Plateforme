<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            //->add('dateCreation', DateTimeType::class, [
              //  'time_label' => 'Starts On',])
            ->add('debutSoumission',DateTimeType::class, [
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'html5' => false,
                'attr' => ['class' => 'comment_input']
            ])
            ->add('finSoumission',DateTimeType::class, [
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'html5' => false,
                'attr' => ['class' => 'comment_input']
            ])
            ->add('periodeGrace')
            ->add('Document',FileType::class, [
                'label' => 'Document',
                'mapped' => false,

                'required' => true,

                'constraints' => [
                    new File([
                        
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('Contenu', TextareaType::class ,[
                'attr' => ['class' => 'tinymce',
                           'required'=>'false'],
            ])
         
           // ->add('section')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
