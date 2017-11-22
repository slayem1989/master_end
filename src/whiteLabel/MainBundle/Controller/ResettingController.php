<?php

namespace whiteLabel\MainBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SebastianBergmann\Exporter\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\ResettingController as BaseResettingController;

/**
 * Class ResettingController
 * @package whiteLabel\MainBundle\Controller
 */
class ResettingController extends BaseResettingController
{
    /**
     * @return Response
     */
    public function requestAction()
    {
        if (array_key_exists('login', $_SESSION) && $_SESSION['login']) {
            $url = $this->generateUrl('fo_homepage');
            $response = new RedirectResponse($url);
            return $response;
        }

        $recaptcha_client_key = $this->getParameter('captcha_client_key');

        $nomDomaine_current = $_SERVER["SERVER_NAME"];
        $nomDomaine_fo = $this->getParameter('url_fo');
        $nomDomaine_bo = $this->getParameter('url_bo');

        if ($nomDomaine_fo == $nomDomaine_current) {
            $var = 'adresse email';
        } elseif ($nomDomaine_bo == $nomDomaine_current) {
            $var = 'nom d\'utilisateur';
        } else {
            $var = 'adresse email';
        }

        return $this->render('@FOSUser/Resetting/request.html.twig', array(
            'recaptcha_client_key'  => $recaptcha_client_key,
            'string'                => $var
        ));
    }

    /**
     * @param Request $request
     * @return null|RedirectResponse|Response
     */
    public function sendEmailAction(Request $request)
    {
        if ($this->captchaVerify($request->get('g-recaptcha-response'))) {
            $username = $request->request->get('username');

            $EM = $this->getDoctrine()->getManager();
            $repo = $EM->getRepository('whiteLabelMainBundle:User');
            $userObject = $repo->findOneBy(array('username' => trim($username)));

            if (null == $userObject || (true != $userObject->isEnabled() && null != $userObject->getLastLogin())) {
                return new RedirectResponse($this->generateUrl('fos_user_resetting_check_email', array('username' => $username)));
                /*
            }

            if (true != $userObject->isEnabled() && null != $userObject->getLastLogin()) {
                $this->addFlash(
                    'danger',
                    'Votre compte a été désactivé.'
                );

                $nomDomaine_current = $_SERVER["SERVER_NAME"];
                $nomDomaine_fo = $this->getParameter('url_fo');
                $nomDomaine_bo = $this->getParameter('url_bo');

                if ($nomDomaine_fo == $nomDomaine_current) {
                    $url = $this->generateUrl('fos_user_security_login');
                } elseif ($nomDomaine_bo == $nomDomaine_current) {
                    $url = $this->generateUrl('fos_admin_user_security_login');
                } else {
                    $url = $this->generateUrl('fos_user_security_login');
                }

                return new RedirectResponse($url);
                */
            } else {
                /** @var $user UserInterface */
                $user = $this->get('fos_user.user_manager')->findUserByUsername($username);
                /** @var $dispatcher EventDispatcherInterface */
                $dispatcher = $this->get('event_dispatcher');

                /* Dispatch init event */
                $event = new GetResponseNullableUserEvent($user, $request);
                $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

                if (null !== $event->getResponse()) {
                    return $event->getResponse();
                }

                $ttl = $this->container->getParameter('fos_user.resetting.retry_ttl');
                if (null !== $user && !$user->isPasswordRequestNonExpired($ttl)) {
                    $event = new GetResponseUserEvent($user, $request);
                    $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);

                    if (null !== $event->getResponse()) {
                        return $event->getResponse();
                    }

                    if (null === $user->getConfirmationToken()) {
                        /** @var $tokenGenerator TokenGeneratorInterface */
                        $tokenGenerator = $this->get('fos_user.util.token_generator');
                        $user->setConfirmationToken($tokenGenerator->generateToken());
                    }

                    /* Dispatch confirm event */
                    $event = new GetResponseUserEvent($user, $request);
                    $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);

                    if (null !== $event->getResponse()) {
                        return $event->getResponse();
                    }

                    $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
                    $user->setPasswordRequestedAt(new \DateTime());
                    $this->get('fos_user.user_manager')->updateUser($user);

                    /* Dispatch completed event */
                    $event = new GetResponseUserEvent($user, $request);
                    $dispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED, $event);

                    if (null !== $event->getResponse()) {
                        return $event->getResponse();
                    }
                }

                return new RedirectResponse($this->generateUrl('fos_user_resetting_check_email', array('username' => $username)));
            }
        } else {
            $this->addFlash(
                'danger',
                'Captcha obligatoire'
            );

            return new RedirectResponse($this->generateUrl('fos_user_resetting_request', array()));
        }
    }

    /**
     * @param $recaptcha
     * @return bool
     */
    public function captchaVerify($recaptcha)
    {
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret"    => $this->getParameter('captcha_server_key'),
            "response"  => $recaptcha
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        if (is_object($data)) $data_success = $data->success;
        else $data_success = false;

        return $data_success;
    }

    /**
     * @param Request $request
     * @param string $token
     * @return Response
     */
    public function resetAction(Request $request, $token)
    {
        if (array_key_exists('login', $_SESSION) && $_SESSION['login']) {
            $url = $this->generateUrl('fo_homepage');
            $response = new RedirectResponse($url);
            return $response;
        }

        /*
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            // Token not found
            $request->getSession()->getFlashBag()->add(
                'danger',
                'Le lien a expiré.'
            );

            return new RedirectResponse($this->container->get('router')->generate('web_homepage'));
        } else {
            // Token found
            return parent::resetAction($request, $token);
        }
        */

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        // Token not found
        if (null === $user) {
            //throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
            $this->addFlash(
                'danger',
                'Le lien a expiré.'
            );

            return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # check password pattern
            $re = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
            if (!preg_match($re,$user->getPlainPassword())) {
                throw new Exception("Unauthorized password. Please contact the administrator.");
            };

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $this->addFlash(
                    'success',
                    'Le mot de passe a été réinitialisé avec succès.'
                );

                $nomDomaine_current = $_SERVER["SERVER_NAME"];
                $nomDomaine_fo = $this->getParameter('url_fo');
                $nomDomaine_bo = $this->getParameter('url_bo');

                if ($nomDomaine_fo == $nomDomaine_current) {
                    $url = $this->generateUrl('fos_user_security_login');
                } elseif ($nomDomaine_bo == $nomDomaine_current) {
                    $url = $this->generateUrl('fos_admin_user_security_login');
                } else {
                    $url = $this->generateUrl('fos_user_security_login');
                }

                /*
                $rolesTab = $user->getRoles();
                if (
                    in_array('ROLE_ADMIN', $rolesTab, true) ||
                    in_array('ROLE_CLIENT', $rolesTab, true) ||
                    in_array('ROLE_AUDITEUR', $rolesTab, true) ||
                    in_array('ROLE_RENOVATEUR', $rolesTab, true) ||
                    in_array('ROLE_INSTRUCTEUR', $rolesTab, true) ||
                    in_array('ROLE_CONSEILLER', $rolesTab, true) ||
                    in_array('ROLE_SUPER_ADMIN', $rolesTab, true)
                ) {
                    $url = $this->generateUrl('fos_admin_user_security_login');
                } elseif (in_array('ROLE_MEMBER', $rolesTab, true)) {
                    $url = $this->generateUrl('fos_user_security_login');
                } else {
                    $url = $this->generateUrl('fos_user_security_login');
                }
                */

                //$url = $this->generateUrl('fos_user_profile_show');

                $response = new RedirectResponse($url);
            }

            /*
            $dispatcher->dispatch(
                FOSUserEvents::RESETTING_RESET_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );
            */

            return $response;
        }

        return $this->render('@FOSUser/Resetting/reset.html.twig', array(
            'token' => $token,
            'form'  => $form->createView(),
        ));
    }
}
