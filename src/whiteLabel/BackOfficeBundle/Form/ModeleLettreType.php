<?php

namespace whiteLabel\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class ModeleLettreType
 * @package whiteLabel\BackOfficeBundle\Form
 */
class ModeleLettreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('typeBeneficiaire',   ChoiceType::class,  array(
                                                                'required'      => true,
                                                                'label'         => 'Type',
                                                                'placeholder'   => '',
                                                                'multiple'      => false,
                                                                'expanded'      => true,
                                                                'empty_data'    => null,
                                                                'choices'       => array(
                                                                    'Particulier'   => '0 | particulier',
                                                                    'Société'       => '1 | societe'
                                                                )
                                                            ))
            ->add('nom',                TextType::class,    array(
                                                                'required'  => true,
                                                                'label'     => 'Nom',
                                                                'attr'      => array(
                                                                    'placeholder' => 'Entrez le nom du modèle',
                                                                )
                                                            ))
            ->add('file',               FileType::class,    array(
                                                                'required'  => $this->traitChoices[0],
                                                                'label'     => 'Fichier de données',
                                                            ))
            ->add('valider',            SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'whiteLabel\BackOfficeBundle\Entity\ModeleLettre',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'whitelabel_backofficebundle_modelelettre';
    }


}
