<?php

namespace Spa\SpaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticlesType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->setMethod('POST')
                ->add('title', 'text', array('required' => true, 'label' => 'Titre', 'error_bubbling' => false))
                ->add('subtitle', 'text', array('required' => true, 'label' => 'Sous-titre', 'error_bubbling' => false))
                ->add('content', 'textarea', array('required' => true, 'label' => 'Contenu de l\'article', 'error_bubbling' => false))
                ->add('investigation', 'checkbox', array('label' => "Article après investigation judiciaire ?", 'required' => false))
                ->add('tagstags', EntityType::class, array('label' => 'Tags', 'class' => 'SpaSpaBundle:Tags', 'choice_label' => 'name', 'multiple' => true))
                ->add('file', FileType::class, array('data_class' => null, 'multiple' => true))
                ->add('save', SubmitType::class, array('label' => "Créer"))
                ->getForm();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spa\SpaBundle\Entity\Articles'
        ));
    }

    public function getName() {
        return 'Articles';
    }

}
