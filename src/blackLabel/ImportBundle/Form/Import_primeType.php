<?php

namespace blackLabel\ImportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class Import_primeType
 * @package blackLabel\ImportBundle\Form
 */
class Import_primeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('type',                           ChoiceType::class,      array(
                                                                                'required'      => true,
                                                                                'label'         => 'Type',
                                                                                'placeholder'   => '',
                                                                                'multiple'      => false,
                                                                                'expanded'      => true,
                                                                                'empty_data'    => null,
                                                                                'choices'       => array(
                                                                                    'LC'    => 'LC',
                                                                                    'VIR'   => 'VIR'
                                                                                )
                                                                            ))
            ->add('date',                           TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Date d\'intégration',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'DD/MM/YYYY',
                                                                                )
                                                                            ))
            ->add('index',                          TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Index',
                                                                                'attr'      => array(
                                                                                    'placeholder'   => 'Entrez l\'index de la Prime',
                                                                                    'readonly'      => true
                                                                                )
                                                                            ))
            ->add('numero',                         TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Numéro de chèque',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le numéro de Chèque de la Prime',
                                                                                )
                                                                            ))
            ->add('prenom',                         TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Prénom',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le prénom',
                                                                                )
                                                                            ))
            ->add('nom',                            TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Nom',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le prénom',
                                                                                )
                                                                            ))
            ->add('siren',                          TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'SIREN',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le SIREN',
                                                                                )
                                                                            ))
            ->add('denomination',                   TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Dénomination',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez la dénomination',
                                                                                )
                                                                            ))
            ->add('representant',                   TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Représentant',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le représentant',
                                                                                )
                                                                            ))
            ->add('adresseFacturation',             TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Adresse',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez l\'adresse',
                                                                                )
                                                                            ))
            ->add('complementFacturation',          TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Complément',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le complément',
                                                                                )
                                                                            ))
            ->add('codePostalFacturation',          TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Code Postal',
                                                                                'attr'      => array(
                                                                                    'placeholder'   => 'Entrez le code postal',
                                                                                    'maxlength'     => 5,
                                                                                    'pattern'       => '^[0-9]{4,5}$'
                                                                                )
                                                                            ))
            ->add('villeFacturation',               TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Ville',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez la ville',
                                                                                )
                                                                            ))
            ->add('paysFacturation',                TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Pays',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le pays',
                                                                                )
                                                                            ))
            ->add('adresseChantier',                TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Adresse',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez l\'adresse',
                                                                                )
                                                                            ))
            ->add('complementChantier',             TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Complément',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le complément',
                                                                                )
                                                                            ))
            ->add('codePostalChantier',             TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Code Postal',
                                                                                'attr'      => array(
                                                                                    'placeholder'   => 'Entrez le code postal',
                                                                                    'maxlength'     => 5,
                                                                                    'pattern'       => '^[0-9]{4,5}$'
                                                                                )
                                                                            ))
            ->add('villeChantier',                  TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Ville',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez la ville',
                                                                                )
                                                                            ))
            ->add('telephone',                      TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Téléphone',
                                                                                'attr'      => array(
                                                                                    'placeholder'   => 'Entrez le téléphone',
                                                                                    'maxlength'     => 14,
                                                                                    'pattern'       => '^0\d(\s|-)?(\d{2}(\s|-)?){4}$'
                                                                                )
                                                                            ))
            ->add('email',                          TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Courriel',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le courriel',
                                                                                )
                                                                            ))
            ->add('iban',                           TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'IBAN',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez l\'IBAN',
                                                                                )
                                                                            ))
            ->add('montantAide',                    TextType::class,        array(
                                                                                'required'  => true,
                                                                                'label'     => 'Montant de l\'aide',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le montant de l\'aide',
                                                                                )
                                                                            ))
            ->add('numeroAction',                   TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Numéro d\'action',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le numéro d\'action',
                                                                                )
                                                                            ))
            ->add('apporteurAffaire',               TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Apporteur d\'affaire',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez l\'apporteur d\'affaire',
                                                                                )
                                                                            ))
            ->add('onglet',                         TextType::class,        array(
                                                                                'required'  => false,
                                                                                'label'     => 'Nom de l\'Onglet',
                                                                                'attr'      => array(
                                                                                    'placeholder' => 'Entrez le nom de l\'onglet',
                                                                                )
                                                                            ))
            ->add('nomModele',                      ChoiceType::class,      array(
                                                                                'required'      => true,
                                                                                'label'         => 'Type',
                                                                                'placeholder'   => '-- Choisir un modèle de Lettre Chèque --',
                                                                                'multiple'      => false,
                                                                                'empty_data'    => null,
                                                                                'choices'       => $this->traitChoices[0]
                                                                            ))
            ->add('valider',                        SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'blackLabel\ImportBundle\Entity\Import_prime',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blacklabel_importbundle_import_prime';
    }
}
