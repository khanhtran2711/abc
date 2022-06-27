<?php
namespace App\Form\Type;

use App\Controller\AnimalController;
use App\Entity\Animal;
use App\Entity\CatAni;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>Animal::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',TextType::class)
        ->add('birthday',DateTimeType::class,[
            'widget' => 'single_text'
        ])
        ->add('email',EmailType::class)
        ->add('weight',TextType::class)
        ->add('cat',EntityType::class,[
            'class' => CatAni::class,
            'choice_label' => 'name'
        ])
        ->add('save',SubmitType::class,[
            'label' => "Save"
        ]);
    }
}

?>