<?php

namespace whiteLabel\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\MainBundle\Entity\User;
use whiteLabel\MainBundle\Form\UserType;

/**
 * Class UserController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF USER
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelMainBundle:User');
        $list = $repo->findBy(
            array(),
            array('id' => 'DESC')
        );

        return $this->render('whiteLabelMainBundle:User:list.html.twig', array(
            'list' => $list
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('plainPassword');
        $form->remove('password');
        $form->remove('enabled');

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $userService = $this->get('white_label.service.user');
            $isExisted = $userService->createUser(
                $user->getUsername(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                array($_POST['whitelabel_mainbundle_user']['roles'])
            );

            if (true == $isExisted){
                $request->getSession()->getFlashBag()->add(
                    'danger',
                    'L\'utilisateur ' . $user->getUsername() . ' existe déjà.'
                );

                return $this->redirectToRoute('user_create', array());
            } else {
                $request->getSession()->getFlashBag()->add(
                    'success',
                    'L\'utilisateur ' . $user->getFirstname() . ' ' . $user->getLastname() . ' a été crée avec succès.'
                );

                return $this->redirectToRoute('user_list', array());
            }
        }

        return $this->render('whiteLabelMainBundle:User:create.html.twig', array(
            'form'  => $form->createView(),
            'user'  => $user
        ));
    }

    /**
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function readAction($userId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET USER
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelMainBundle:User');
        $user = $repo->find($userId);

        return $this->render('whiteLabelMainBundle:User:read.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @param Request $request
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $userId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET USER
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelMainBundle:User');
        $user = $repo->find($userId);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $roles = $user->getRoles();
        $form = $this->createForm(UserType::class, $user, array(
            'trait_choices' => $roles
        ));
        $form->remove('plainPassword');
        $form->remove('password');
        $form->remove('enabled');

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $userService = $this->get('white_label.service.user');
            $userService->updateUser(
                $user->getId(),
                $user->getUsername(),
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                array($_POST['whitelabel_mainbundle_user']['roles'])
            );

            $request->getSession()->getFlashBag()->add(
                'success',
                'L\'utilisateur ' . $user->getUsername() . ' a bien été modifié.'
            );

            return $this->redirectToRoute('user_list', array());
        }

        return $this->render('whiteLabelMainBundle:User:update.html.twig', array(
            'form'  => $form->createView(),
            'user'  => $user
        ));
    }
}
