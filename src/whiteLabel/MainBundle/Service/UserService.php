<?php

namespace whiteLabel\MainBundle\Service;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class UserService
 * @package whiteLabel\MainBundle\Service
 */
class UserService
{
    private $connMySQL = null;

    /**
     * UserService constructor.
     * @param $doctrine
     */
    public function __construct($doctrine)
    {
        $this->connMySQL = $doctrine->getManager()->getConnection();
    }

    /**
     * @param $userId_object
     * @param $userId_session
     * @param $userId_param
     */
    public function checkUser($userId_object, $userId_session, $userId_param)
    {
        if (($userId_object != $userId_session) || ($userId_object != $userId_param) || ($userId_session != $userId_param)) {
            throw new AccessDeniedHttpException("Unauthorized access. Please contact the administrator.");
        }
    }
}
