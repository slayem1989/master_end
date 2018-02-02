<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use IBAN\Validation\IBANValidator;

use whiteLabel\BackOfficeBundle\Entity\Client_;
use whiteLabel\BackOfficeBundle\Form\Client_Type;

/**
 * Class ClientController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_COORDINATEUR') or has_role('ROLE_ADMIN')")
 */
class ClientController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF CLIENT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Client_');
        $list = $repo->findBy(
            array(),
            array('id' => 'DESC')
        );

        return $this->render('whiteLabelBackOfficeBundle:Client:list.html.twig', array(
            'list' => $list
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $formOption = array();
        $formOption[] = true;
        $client = new Client_();
        $form = $this->createForm(Client_Type::class, $client, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            /* /////////////////////////////////////////////////////////////////
                                        IBAN CHECK
            ///////////////////////////////////////////////////////////////// */
            $IBANValidator = new IBANValidator();
            $post_banque = $client->getBanque();
            $isIBANValid = true;
            foreach ($post_banque as $item) {
                $post_iban = $item->getIban();
                if (isset($post_iban) && false == $IBANValidator->validate($post_iban)) {
                    $isIBANValid = false;
                    break;
                }
            }

            if (true != $isIBANValid) {
                $request->getSession()->getFlashBag()->add(
                    'danger',
                    'Les coordonnées bancaires sont erronées.'
                );
            } else {
                $EM->persist($client);
                $EM->flush();
                $EM->clear();

                $request->getSession()->getFlashBag()->add(
                    'success',
                    'Le Client ' . $client->getClientInformation()->getNom() . ' a été créé avec succès.'
                );
            }

            return $this->redirectToRoute('client_list', array());
        }

        return $this->render('whiteLabelBackOfficeBundle:Client:create.html.twig', array(
            'form'      => $form->createView(),
            'client'    => $client
        ));
    }

    /**
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function readAction($clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET CLIENT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Client_');
        $client = $repo->find($clientId);

        return $this->render('whiteLabelBackOfficeBundle:Client:read.html.twig', array(
            'client' => $client
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET CLIENT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Client_');
        $client = $repo->find($clientId);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $formOption = array();
        $formOption[] = false;
        $form = $this->createForm(Client_Type::class, $client, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $client->setDateModif(new \Datetime());
            $client->setAuteurModif($_SESSION['login']->getUsername());

            /* /////////////////////////////////////////////////////////////////
                                        IBAN CHECK
            ///////////////////////////////////////////////////////////////// */
            $IBANValidator = new IBANValidator();
            $post_banque = $client->getBanque();
            $isIBANValid = true;
            foreach ($post_banque as $item) {
                $post_iban = $item->getIban();
                if (isset($post_iban) && false == $IBANValidator->validate($post_iban)) {
                    $isIBANValid = false;
                    break;
                }
            }

            if (true != $isIBANValid) {
                $request->getSession()->getFlashBag()->add(
                    'danger',
                    'Les coordonnées bancaires sont erronées.'
                );
            } else {
                $EM->persist($client);
                $EM->flush();
                $EM->clear();

                $request->getSession()->getFlashBag()->add(
                    'success',
                    'Le Client ' . $client->getClientInformation()->getNom() . ' a bien été modifié.'
                );
            }

            return $this->redirectToRoute('client_list', array());
        }

        return $this->render('whiteLabelBackOfficeBundle:Client:update.html.twig', array(
            'form'      => $form->createView(),
            'client'    => $client
        ));
    }
}
