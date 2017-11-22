<?php

namespace whiteLabel\MainBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use SebastianBergmann\Exporter\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;

/**
 * Class RegistrationController
 * @package whiteLabel\MainBundle\Controller
 */
class RegistrationController extends BaseRegistrationController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if (array_key_exists('login', $_SESSION) && $_SESSION['login']) {
            $url = $this->generateUrl('fo_homepage');
            $response = new RedirectResponse($url);
            return $response;
        }

        $recaptcha_client_key = $this->getParameter('captcha_client_key');

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->remove('roles');
        $form->remove('enabled');

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                # check password pattern
                $re = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
                if (!preg_match($re,$user->getPlainPassword())) {
                    throw new Exception("Unauthorized password. Please contact the administrator.");
                };

                # check if form is submitted and Recaptcha response is success
                if (!$this->captchaVerify($request->get('g-recaptcha-response'))) {
                    $this->addFlash(
                        'danger',
                        'Captcha obligatoire'
                    );

                    return new RedirectResponse($this->generateUrl('fos_user_registration_register', array()));
                }

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form'                  => $form->createView(),
            'recaptcha_client_key'  => $recaptcha_client_key
        ));
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
     * @return null|RedirectResponse|Response
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $request->getSession()->getFlashBag()->add(
                'success',
                'Votre compte a été activé avec succès.'
            );

            //$url = $this->generateUrl('fos_user_registration_confirmed');
            $url = $this->generateUrl('fos_user_security_login');
            $response = new RedirectResponse($url);
        }

        /*
        $dispatcher->dispatch(
            FOSUserEvents::REGISTRATION_CONFIRMED,
            new FilterUserResponseEvent($user, $request, $response)
        );
        */

        return $response;
    }
}
