<?php

namespace whiteLabel\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use whiteLabel\BackOfficeBundle\Repository\Client_banqueRepository;

/**
 * Class Cheque_stockType
 * @package whiteLabel\BackOfficeBundle\Form
 */
class Cheque_stockType extends AbstractType
{
    protected $EM;
    protected $repo_banque;

    /**
     * Cheque_stockType constructor.
     * @param Doctrine $EM
     */
    public function __construct(Doctrine $EM)
    {
        $this->EM = $EM;
        $this->repo_banque = $this->EM->getRepository('whiteLabelBackOfficeBundle:Client_banque');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->traitChoices = $options['trait_choices'];

        $builder
            ->add('banque_id',      EntityType::class,  array(
                                                            'required'      => true,
                                                            'placeholder'   => '-- Choisir une banque --',
                                                            'label'         => false,
                                                            'class'         => 'whiteLabelBackOfficeBundle:Client_banque',
                                                            'query_builder' => function(Client_banqueRepository $r) {
                                                                return $r->findByClient($this->traitChoices[0]);
                                                            },
                                                            'choice_label'  => function ($obj) {
                                                                return   $obj->getNom();
                                                            },
                                                            'choice_value'  => 'id',
                                                            'data'          => $this->repo_banque->findOneBy(['id' => $this->traitChoices[1]])
                                                        ))
            ->add('reference_boite',TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Référence boîte',
                                                            'attr'      => array(
                                                                'placeholder'   => 'Entrez la référence de la boîte',
                                                            )
                                                        ))
            ->add('first',          TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Premier chèque',
                                                            'attr'      => array(
                                                                'placeholder'   => 'Entrez le premier numéro de chèque',
                                                            )
                                                        ))
            ->add('last',           TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Dernier chèque',
                                                            'attr'      => array(
                                                                'placeholder'   => 'Entrez le dernier numéro de chèque',
                                                            )
                                                        ))
            ->add('count',          TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Nombre de chèque',
                                                            'attr'      => array(
                                                                'readonly'  => true
                                                            )
                                                        ))
            ->add('date_reception', TextType::class,    array(
                                                            'required'  => true,
                                                            'label'     => 'Date réception',
                                                            'attr'      => array(
                                                                'placeholder'   => 'DD/MM/YYYY'
                                                            )
                                                        ))
            ->add('valider',        SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'whiteLabel\BackOfficeBundle\Entity\Cheque_stock',
            'trait_choices' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'whitelabel_backofficebundle_cheque_stock';
    }
}
