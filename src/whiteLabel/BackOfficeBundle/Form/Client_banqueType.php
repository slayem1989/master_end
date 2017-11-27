<?php

namespace whiteLabel\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class Client_banqueType
 * @package whiteLabel\BackOfficeBundle\Form
 */
class Client_banqueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('nom',        TextType::class,    array(
                                                        'required'      => false,
                                                        'label'         => 'Banque',
                                                        'label_attr'    => array(
                                                            'class' => 'col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label'
                                                        ),
                                                        'attr'          => array(
                                                            'placeholder'   => 'Entrez le nom de la banque',
                                                            'class'         => 'form-control'
                                                        )
                                                    ))
            ->add('rib',        TextType::class,    array(
                                                        'required'      => false,
                                                        'label'         => 'RIB',
                                                        'label_attr'    => array(
                                                            'class' => 'col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label'
                                                        ),
                                                        'attr'          => array(
                                                            'placeholder'   => 'Entrez le RIB',
                                                            'class'         => 'form-control'
                                                        )
                                                    ))
            ->add('iban',       TextType::class,    array(
                                                        'required'      => false,
                                                        'label'         => 'IBAN',
                                                        'label_attr'    => array(
                                                            'class' => 'col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label'
                                                        ),
                                                        'attr'          => array(
                                                            'placeholder'   => 'Entrez l\'IBAN',
                                                            'class'         => 'form-control control_iban'
                                                        )
                                                    ))
            ->add('bic',        TextType::class,    array(
                                                        'required'      => false,
                                                        'label'         => 'BIC',
                                                        'label_attr'    => array(
                                                            'class' => 'col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label'
                                                        ),
                                                        'attr'          => array(
                                                            'placeholder'   => 'Entrez le BIC',
                                                            'class'         => 'form-control'
                                                        )
                                                    ))
            ->add('titulaire',  TextType::class,    array(
                                                        'required'      => false,
                                                        'label'         => 'Titulaire',
                                                        'label_attr'    => array(
                                                            'class' => 'col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label'
                                                        ),
                                                        'attr'          => array(
                                                            'placeholder'   => 'Entrez le titulaire',
                                                            'class'         => 'form-control'
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
            'data_class'    => 'whiteLabel\BackOfficeBundle\Entity\Client_banque',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'whitelabel_backofficebundle_client_banque';
    }


}
