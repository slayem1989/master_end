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
     * @param array $option
     * @param string $data
     * @return mixed
     */
    public function updateValidateType($option=array(), $data='')
    {
        $form = $this->formFactory->createBuilder()
            ->add('date',       TextType::class,    array(
                                                        'required'  => true,
                                                        'label'     => 'Date de validation',
                                                        'attr'      => array(
                                                            'placeholder' => 'DD/MM/YYYY',
                                                        )
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
        $form = $this->formFactory->createNamedBuilder('form_deny'.$statutId)
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
