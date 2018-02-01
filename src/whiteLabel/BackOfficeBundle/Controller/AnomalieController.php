<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AnomalieController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class AnomalieController extends Controller
{
    public function listPNDAction($clientId)
    {

    }

    public function listDesistementAction($clientId)
    {

    }

    public function createAction($clientId)
    {

    }

    public function readAction($clientId, $anomalieId)
    {

    }

    public function updateAction($clientId, $anomalieId)
    {

    }
}
