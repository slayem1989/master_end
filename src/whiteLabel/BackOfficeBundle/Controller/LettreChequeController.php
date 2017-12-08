<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\BackOfficeBundle\Entity\LettreCheque;
use whiteLabel\BackOfficeBundle\Form\LettreChequeType;

/**
 * Class LettreChequeController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class LettreChequeController extends Controller
{
    /**
     * @param $clientId
     * @return Response
     */
    public function listAction($clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                            GET LIST OF LETTRE CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:LettreCheque');
        $list = $repo->findByClient($clientId);

        return $this->render('whiteLabelBackOfficeBundle:LettreCheque:list.html.twig', array(
            'list'      => $list,
            'clientId'  => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request, $clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $lettreCheque = new LettreCheque();
        $form = $this->createForm(LettreChequeType::class, $lettreCheque);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $lettreCheque->setClientId($clientId);

            $EM->persist($lettreCheque);
            $EM->flush();

            /* //////////////////////////////////////////////////////////
                                    PERSIST DATA
            /////////////////////////////////////////////////////////// */
            $importService = $this->get('white_label.service.lettreCheque');
            $importService->persistHTML(
                $lettreCheque->getId(),
                $lettreCheque->file_getWebPath()
            );

            $request->getSession()->getFlashBag()->add(
                'success',
                'La Lettre Chèque ' . $lettreCheque->getNomModele() . ' a été créée avec succès.'
            );

            return $this->redirectToRoute('lettreCheque_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:LettreCheque:create.html.twig', array(
            'form'          => $form->createView(),
            'lettreCheque'  => $lettreCheque,
            'clientId'      => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $lettreChequeId
     * @return Response
     */
    public function readAction($clientId, $lettreChequeId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LETTRE CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:LettreCheque');
        $lettreCheque = $repo->find($lettreChequeId);

        return $this->render('whiteLabelBackOfficeBundle:LettreCheque:read.html.twig', array(
            'lettreCheque'  => $lettreCheque,
            'clientId'      => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @param $lettreChequeId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateAction(Request $request, $clientId, $lettreChequeId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LETTRE CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:LettreCheque');
        $lettreCheque = $repo->find($lettreChequeId);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $form = $this->createForm(LettreChequeType::class, $lettreCheque);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $lettreCheque->setDateModif(new \Datetime());
            $lettreCheque->setAuteurModif($_SESSION['login']->getUsername());

            $EM->persist($lettreCheque);
            $EM->flush();

            /* //////////////////////////////////////////////////////////
                                    PERSIST DATA
            /////////////////////////////////////////////////////////// */
            $importService = $this->get('white_label.service.lettreCheque');
            $importService->persistHTML(
                $lettreCheque->getId(),
                $lettreCheque->file_getWebPath()
            );

            $request->getSession()->getFlashBag()->add(
                'success',
                'La Lettre Chèque ' . $lettreCheque->getNomModele() . ' a bien été mise à jour.'
            );

            return $this->redirectToRoute('lettreCheque_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:LettreCheque:update.html.twig', array(
            'form'          => $form->createView(),
            'lettreCheque'  => $lettreCheque,
            'clientId'      => $clientId
        ));
    }
}
