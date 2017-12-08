<?php

namespace whiteLabel\BackOfficeBundle\Service;

/**
 * Class SecurityService
 * @package whiteLabel\BackOfficeBundle\Service
 */
class SecurityService
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
     * SecurityService constructor.
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
    /**
     * @param $clientSession
     * @param $clientParameter
     */
    public function checkClient($clientSession, $clientParameter)
    {

    }

    public function preventResubmit()
    {

    }

    /* *****************************************************************
    ********************************************************************
                            F U N C T I O N S
    ********************************************************************
    *******************************************************************/
}
