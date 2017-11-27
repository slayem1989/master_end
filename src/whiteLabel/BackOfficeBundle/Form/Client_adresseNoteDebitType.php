<?php

namespace whiteLabel\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class Client_adresseNoteDebitType
 * @package whiteLabel\BackOfficeBundle\Form
 */
class Client_adresseNoteDebitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('destinataire',   TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Destinataire',
                                                            'attr'      => array(
                                                                'placeholder' => 'Entrez un destinataire'
                                                            )
                                                        ))
            ->add('complement1',    TextType::class,    array(
                                                            'required'  => false,
                                                            'label'     => 'Complément 1',
                                                            'attr'      => array(
                                                                'placeholder' => 'Entrez un complément'
                                                            )
                                                        ))
            ->add('complement2',    TextType::class,    array(
                                                            'required'  => false,
                                                            'label'     => 'Complément 2',
                                                            'attr'      => array(
                                                                'placeholder' => 'Entrez un complément'
                                                            )
                                                        ))
            ->add('adresse',        TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Adresse',
                                                            'attr'      => array(
                                                                'placeholder' => 'Entrez une adresse'
                                                            )
                                                        ))
            ->add('codePostal',     TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Code Postal',
                                                            'attr'      => array(
                                                                'placeholder'   => 'Entrez un code postal',
                                                                'maxlength'     => 5,
                                                                'pattern'       => '^[0-9]{4,5}$',
                                                            )
                                                        ))
            ->add('ville',          TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Ville',
                                                            'attr'      => array(
                                                                'placeholder' => 'Entrez une ville'
                                                            )
                                                        ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'whiteLabel\BackOfficeBundle\Entity\Client_adresseNoteDebit',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'whitelabel_backofficebundle_client_adressenotedebit';
    }


}
