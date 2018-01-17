<?php

namespace whiteLabel\BackOfficeBundle\Service;

use Spipu\Html2Pdf\Html2Pdf;

/**
 * Class LettreChequeService
 * @package whiteLabel\BackOfficeBundle\Service
 */
class LettreChequeService
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
     * LettreChequeService constructor.
     * @param $doctrine
     * @param $container
     */
    public function __construct(
        $doctrine,
        $container
    ) {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }



    /* *****************************************************************
    ********************************************************************
                            F I N D / S E A R C H
    ********************************************************************
    *******************************************************************/

    /* *****************************************************************
    ********************************************************************
                            F U N C T I O N S
    ********************************************************************
    *******************************************************************/
}
