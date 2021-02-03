<?php

namespace App\Form;

use App\Entity\VideoConference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class VideoConferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            //->add('Lien')
          //  ->add('IdReunion')
          //  ->add('code')
            ->add('date',DateTimeType::class, [
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd HH:mm',
                'html5' => false,
                'attr' => ['class' => 'comment_input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VideoConference::class,
        ]);
    }
}
