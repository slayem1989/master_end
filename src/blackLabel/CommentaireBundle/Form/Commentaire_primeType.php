<?php

namespace blackLabel\CommentaireBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class Commentaire_primeType
 * @package blackLabel\CommentaireBundle\Form
 */
class Commentaire_primeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('prime_id',       HiddenType::class,      array(
                                                                'required'  => true,
                                                                'label'     => 'Id de la prime',
                                                                'attr'      => array(
                                                                    'placeholder'   => '',
                                                                    'readonly'      => true
                                                                )
                                                            ))
            ->add('content',        TextareaType::class,    array(
                                                                'required'      => true,
                                                                'label'         => false,
                                                                'attr'          => array(
                                                                    'placeholder'   => 'Entrez le contenu du commentaire (limité à 245 caractères)',
                                                                    'maxlength'     => '245'
                                                                )
                                                            ))
            ->add('enregistrer',    SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'blackLabel\CommentaireBundle\Entity\Commentaire_prime',
            'trait_choices'     => null,
            'csrf_protection'   => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blacklabel_commentairebundle_commentaire_prime';
    }
}
