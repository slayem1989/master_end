<?php

namespace whiteLabel\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class Client_Type
 * @package whiteLabel\BackOfficeBundle\Form
 */
class Client_Type extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('client_information',         Client_informationType::class,          array(
                                                                                            'label'         => false,
                                                                                            'trait_choices' => $this->traitChoices
                                                                                        ))
            ->add('client_adresseFacturation',  Client_adresseFacturationType::class,   array(
                                                                                            'label'         => false,
                                                                                            'trait_choices' => $this->traitChoices
                                                                                        ))
            ->add('client_adresseNoteDebit',    Client_adresseNoteDebitType::class,     array(
                                                                                        'label'         => false,
                                                                                        'trait_choices' => $this->traitChoices
                                                                                        ))
            ->add('banque',                     CollectionType::class,                  array(
                                                                                            'entry_type'    => Client_banqueType::class,
                                                                                            'label'         => false,
                                                                                            'allow_add'     => true,
                                                                                            'allow_delete'  => true,
                                                                                            'required'      => false,
                                                                                            'prototype'     => true,
                                                                                            'by_reference'  => false
                                                                                        ))
            ->add('logo',                       FileType::class,                        array(
                                                                                            'required'  => true,
                                                                                            'label'     => 'Logo',
                                                                                        ))
            ->add('valider',                    SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'whiteLabel\BackOfficeBundle\Entity\Client_',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'whitelabel_backofficebundle_client_';
    }


}
