<?php

namespace whiteLabel\BackOfficeBundle\Service;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class LotService
 * @package whiteLabel\BackOfficeBundle\Service
 */
class LotService
{
    /**
     * @var string
     */
    private $doctrine;

    /**
     * @var string
     */
    private $container;

    /**
     * @var string
     */
    private $formFactory;


    /**
     * LotService constructor.
     * @param $doctrine
     * @param $container
     */
    public function __construct(
        $doctrine,
        $container
    ) {
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->formFactory = $container->get('form.factory');
    }



    /* *****************************************************************
    ********************************************************************
                                F O R M
    ********************************************************************
    *******************************************************************/
    /**
     * @return mixed
     */
    public function updateValidateType()
    {
        $now = new \Datetime();
        $form = $this->formFactory->createBuilder()
            ->add('date',       TextType::class,    array(
                                                        'required'  => true,
                                                        'label'     => 'Date de validation',
                                                        'attr'      => array(
                                                            'placeholder' => 'DD/MM/YYYY',
                                                        ),
                                                        'data'      => $now->format('d/m/Y')
                                                    ))
            ->add('valider',    SubmitType::class)
            ->getForm()
        ;

        return $form;
    }

    /**
     * @param $statutId
     * @return mixed
     */
    public function updateDenyType($statutId)
    {
        $now = new \Datetime();
        $form = $this->formFactory->createNamedBuilder('form_deny'.$statutId)
            ->add('date',       TextType::class,    array(
                                                        'required'  => true,
                                                        'label'     => 'Date de refus',
                                                        'attr'      => array(
                                                            'placeholder' => 'DD/MM/YYYY',
                                                        ),
                                                        'data'      => $now->format('d/m/Y')
                                                    ))
            ->add('refuser',    SubmitType::class)
            ->getForm()
        ;

        return $form;
    }

    /* *****************************************************************
    ********************************************************************
                            F U N C T I O N S
    ********************************************************************
    *******************************************************************/
}
