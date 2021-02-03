<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
         
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
                            'application/*',

                        ],
                       
                    ])
                ],
            ])
            ->add('Contenu', TextareaType::class ,[
                'attr' => ['class' => 'tinymce',
                           'required'=>'false'],
            ])
         
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'TD' => "TD",
                    'Test' => "Test",
                    'QCM' => "QCM",
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
