<?php

namespace Spa\SpaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StaffType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->setMethod('POST')
                ->add('firstname', 'text', array('required' => true, 'label' => 'Prénom', 'error_bubbling' => false))
                ->add('lastname', 'text', array('required' => true, 'label' => 'Nom', 'error_bubbling' => false))
                ->add('sex', 'choice', array('choices' => array(0 => 'Masculin', 1 => 'Féminin'),
                                             'multiple' => false, 'expanded' => false, 'label' => "Sexe"))
                ->add('role', 'text', array('label' => "Rôle au sein de l'association", 'required' => true))
                ->add('file', FileType::class, array('data_class' => null, 'multiple' => false, 'label' => 'Photo'))
                ->add('save', SubmitType::class, array('label' => "Créer"))
                ->getForm();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spa\SpaBundle\Entity\Staff'
        ));
    }

    public function getName() {
        return 'Staff';
    }

}
