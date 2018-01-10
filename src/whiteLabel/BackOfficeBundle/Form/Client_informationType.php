<?php

namespace whiteLabel\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class Client_informationType
 * @package whiteLabel\BackOfficeBundle\Form
 */
class Client_informationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('nom',                TextType::class,    array(
                                                                'required'  => true,
                                                                'label'     => 'Nom',
                                                                'attr'      => array(
                                                                    'placeholder' => 'Entrez le nom du client'
                                                                )
                                                            ))
            ->add('interlocuteur',      TextType::class,    array(
                                                                'required'  => true,
                                                                'label'     => 'Interlocuteur',
                                                                'attr'      => array(
                                                                    'placeholder' => 'Entrez un interlocuteur'
                                                                )
                                                            ))
            ->add('titreDispositif',    TextType::class,    array(
                                                                'required'  => false,
                                                                'label'     => 'Titre Dispositif',
                                                                'attr'      => array(
                                                                    'placeholder' => 'Entrez un titre de dispositif'
                                                                )
                                                            ))
            ->add('logo',               FileType::class,    array(
                                                                'required'  => true,
                                                                'label'     => 'Logo',
                                                            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'whiteLabel\BackOfficeBundle\Entity\Client_information',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'whitelabel_backofficebundle_client_information';
    }


}
