<?php

namespace blackLabel\ImportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use whiteLabel\BackOfficeBundle\Repository\Client_banqueRepository;

/**
 * Class Import_lotType
 * @package blackLabel\ImportBundle\Form
 */
class Import_lotType extends AbstractType
{
    protected $EM;
    protected $repo_banque;

    /**
     * Import_lotType constructor.
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

        $varData = false;
        if (1 == $this->traitChoices[1]) {
            $varData = $this->repo_banque->findOneByClient($this->traitChoices[0]);
        }

        $builder
            ->add('banqueId',   EntityType::class,  array(
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
                                                        'data'          => $varData
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
