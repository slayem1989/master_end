<?php

namespace whiteLabel\MainBundle\Service;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserService
 * @package whiteLabel\MainBundle\Service
 */
class UserService
{
    /**
     * @var string
     */
    private $doctrine;

    /**
     * @var string
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var string
     */
    private $router;

    /**
     * @var string
     */
    private $container;

    /**
     * @var
     */
    private $roleHierarchy;

    /**
     * AdminService constructor.
     * @param $doctrine
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $environment
     * @param $router
     * @param $container
     * @param RoleHierarchyInterface $roleHierarchy
     */
    public function __construct
    (
        $doctrine,
        \Swift_Mailer $mailer,
        \Twig_Environment $environment,
        $router,
        $container,
        RoleHierarchyInterface $roleHierarchy
    )
    {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
        $this->environment = $environment;
        $this->router = $router;
        $this->container = $container;
        $this->roleHierarchy = $roleHierarchy;
    }

    /* *******************************************************************
     * *******************************************************************
                            F I N D / S E A R C H
     * *******************************************************************
     * ***************************************************************** */

    /**
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $roles
     * @return bool
     */
    public function createUser($username, $firstName, $lastName, $email, $roles)
    {
        $EM = $this->doctrine->getManager();
        $isExisted = false;

        $repo = $EM->getRepository('whiteLabelMainBundle:User');
        $userObject = $repo->findOneBy(array(
            'username' => $username
        ));

        if ($userObject) {
            $isExisted = true;
        } else {
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->createUser();

            $user->setUsername(trim($username));
            $user->setFirstname(trim($firstName));
            $user->setLastname(trim($lastName));
            $user->setEmail(trim($email));
            $user->setPlainPassword(trim($username));
            $user->setEnabled(false);
            $user->setRoles($roles);
            $user->setDateInactif(new \Datetime());

            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
            $user->setPasswordRequestedAt(new \Datetime());

            $EM->persist($user);
            $EM->flush();

            //$userManager->updateUser($user, true);

            $this->sendEmail(
                $user->getUsername(),
                $user->getConfirmationToken(),
                $user->getEmail(),
                $user->getRoles()
            );
        }

        return $isExisted;
    }

    /**
     * @param $id
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $roles
     * @return bool
     */
    public function updateUser($id, $username, $firstName, $lastName, $email, $roles)
    {
        $EM = $this->doctrine->getManager();
        $isExisted = false;
        $isDifferent = false;

        $repo = $EM->getRepository('whiteLabelMainBundle:User');
        $userObject = $repo->findOneBy(array(
            'id' => $id
        ));

        if ($userObject) {
            $isExisted = true;

            $userObject->setDateModif(new \Datetime());
            $userObject->setAuteurModif($_SESSION['login']->getUsername());

            $userObject->setUsername(trim($username));
            $userObject->setFirstname(trim($firstName));
            $userObject->setLastname(trim($lastName));
            $userObject->setEmail(trim($email));
            $userObject->setRoles($roles);
            if (trim($email) != $userObject->getEmail()) {
                $userObject->setEnabled(false);

                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $userObject->setConfirmationToken($tokenGenerator->generateToken());
                $userObject->setPasswordRequestedAt(new \Datetime());

                $isDifferent = true;
            }

            $EM->persist($userObject);
            $EM->flush();

            if (true == $isDifferent) {
                $this->sendEmail(
                    $userObject->getUsername(),
                    $userObject->getConfirmationToken(),
                    $userObject->getEmail(),
                    $userObject->getRoles()
                );
            }
        }

        return $isExisted;
    }

    /**
     * @param $role
     * @param $user
     * @return bool
     */
    public function isGranted($role, $user)
    {
        $role = new Role($role);

        foreach ($user->getRoles() as $userRole) {
            if (in_array($role, $this->roleHierarchy->getReachableRoles(array(new Role($userRole)))))
                return true;
        }

        return false;
    }

    /**
     * @param $userId_session
     * @param $userId_param
     */
    public function checkUser($userId_session, $userId_param)
    {
        if ($userId_session != $userId_param) {
            throw new AccessDeniedHttpException("Unauthorized access. Please contact the administrator.");
        }
    }


    /* *******************************************************************
     * *******************************************************************
                                F U N C T I O N S
     * *******************************************************************
     * ***************************************************************** */

    /**
     * @param $username
     * @param $token
     * @param $email
     * @param $roles
     * @return int
     */
    private function sendEmail($username, $token, $email, $roles)
    {
        switch ($roles[0]) {
            case 'ROLE_ADMIN' :
                $userType = 'Administrateur';
                break;
            case 'ROLE_COORDINATEUR' :
                $userType = 'Coordinateur';
                break;
            case 'ROLE_GESTIONNAIRE' :
                $userType = 'Gestionnaire';
                break;
            case 'ROLE_MEMBER' :
                $userType = 'Visiteur';
                break;
            default:
                $userType = 'Visiteur';
                break;
        }

        $subject = 'Activation de votre nouveau compte';
        $templatePath = 'whiteLabelMainBundle:User:email/activation.html.twig';

        $message = (
        \Swift_Message::newInstance(
            $subject, $this->environment->render($templatePath, array(
            'username'        => $username,
            'type'            => $userType,
            'confirmationUrl' => $this->router->generate('fos_user_resetting_reset', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL)
        )), 'text/html')
            ->setFrom($this->container->getParameter('mailer_address_from'))
            ->setTo(trim($email))
        );

        return $this->mailer->send($message);
    }
}

