<?php

namespace whiteLabel\MainBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class UserType
 * @package whiteLabel\MainBundle\Form
 */
class UserType extends BaseType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('firstname',  TextType::class,        array(
                                                            'required'  => true,
                                                            'label'     => 'Prénom',
                                                            'attr'      => array(
                                                                'placeholder' => 'Prénom',
                                                            )
                                                        ))
            ->add('lastname',   TextType::class,        array(
                                                            'required'  => true,
                                                            'label'     => 'Nom',
                                                            'attr'      => array(
                                                                'placeholder' => 'Nom',
                                                            )
                                                        ))
            ->add('roles',      ChoiceType::class,      array(
                                                            'required'      => true,
                                                            'property_path' => 'enabled',
                                                            'empty_data'    => null,
                                                            'multiple'      => false,
                                                            'placeholder'   => 'Choisir un profil',
                                                            'choices' => array(
                                                                'Administrateur'    => 'ROLE_ADMIN',
                                                                'Coordinateur'      => 'ROLE_COORDINATEUR',
                                                                'Gestionnaire'      => 'ROLE_GESTIONNAIRE'
                                                            ),
                                                            'data'          => $this->traitChoices[0][0]
                                                        ))
            ->add('isRoleAdmin', CheckboxType::class, array(
                                                              'label'    => 'Role Admin',
                                                              'required' => false,
                                                              'mapped'   => false,
                                                              'data'     => ($this->traitChoices[1] === true) ? $this->traitChoices[0] : false,
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
            'data_class'    => 'whiteLabel\MainBundle\Entity\User',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'whitelabel_mainbundle_user';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
