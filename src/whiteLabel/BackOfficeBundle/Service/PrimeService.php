<?php

namespace whiteLabel\BackOfficeBundle\Service;

/**
 * Class PrimeService
 * @package whiteLabel\BackOfficeBundle\Service
 */
class PrimeService
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
     * PrimeService constructor.
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
