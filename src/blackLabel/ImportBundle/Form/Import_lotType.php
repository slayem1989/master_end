<?php

namespace blackLabel\ImportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class Import_lotType
 * @package blackLabel\ImportBundle\Form
 */
class Import_lotType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('client',     ChoiceType::class,  array(
                                                        'required'      => true,
                                                        'label'         => false,
                                                        'placeholder'   => '-- Choisir un Client --',
                                                        'empty_data'    => null,
                                                        'choices'       => array(
                                                            'Total' => $this->traitChoices[0],
                                                        )
                                                    ))
            ->add('file',       FileType::class,    array(
                                                        'required'  => true,
                                                        'label'     => 'Fichier de données',
                                                    ))
            ->add('valider',    SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'blackLabel\ImportBundle\Entity\Import_lot',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blacklabel_importbundle_import_lot';
    }


}
