<?php

namespace Spa\SpaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PetsType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->setMethod('POST')
                ->add('idpets', 'hidden', array('required' => false))
                ->add('reference', 'text', array('required' => false, 'label' => 'Numéro du box ou de médaille', 'error_bubbling' => false))
                ->add('type', 'choice', array('choices' => array(0 => 'Chien', 1 => 'Chat', 2 => 'Autre'), 
                                                            'multiple' => false, 
                                                            'expanded' => false))
                ->add('racesraces', EntityType::class, array('label' => 'Race', 'class' => 'SpaSpaBundle:Races', 'choice_label' => 'name', 'multiple' => false, 'empty_value' => ''))
                ->add('sex', 'choice', array('required' => true, 'choices' => array(0 => 'Mâle', 1 => 'Femelle'),
                                             'multiple' => false, 'expanded' => false, 'label' => "Sexe"))
                ->add('size', 'choice', array('required' => true, 'choices' => array(0 => 'Petit', 1 => 'Moyen', 2 => 'Grand'),
                                             'multiple' => false, 'expanded' => false, 'label' => "Taille"))
                ->add('birthdate', 'birthday', array('label' => 'Date de naissance', 'widget' => 'choice', 'input' => 'timestamp','format' => 'd/M/y',
                                                   'empty_value' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),
                                                   'pattern' => "{{ day }}/{{ month }}/{{ year }}"))
                ->add('arrivaldate', 'date', array('label' => 'Date d\'arrivée', 'widget' => 'choice', 'input' => 'timestamp','format' => 'd/M/y',
                                                   'empty_value' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),
                                                   'pattern' => "{{ day }}/{{ month }}/{{ year }}"))
                ->add('petofmonth', 'checkbox', array('label' => "Animal du mois ?", 'required' => false))
                ->add('description', 'textarea', array('required' => true, 'label' => 'Description', 'error_bubbling' => false))
                ->add('filePicture', FileType::class, array('data_class' => null, 'multiple' => true, 'label' => 'Photos'))
                ->add('fileVideo', FileType::class, array('data_class' => null, 'multiple' => true, 'label' => 'Vidéos'))
                
                ->add('save', SubmitType::class, array('label' => "Créer"))
                ->getForm();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spa\SpaBundle\Entity\Pets'
        ));
    }

    public function getName() {
        return 'Pets';
    }

}
