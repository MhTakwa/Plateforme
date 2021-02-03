<?php

namespace App\Form;

use App\Entity\Soumission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SoumissionType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('document',FileType::class, [
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
            ->add('contenu')
            ->add('titre')
          
      
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Soumission::class,
        ]);
    }
}
