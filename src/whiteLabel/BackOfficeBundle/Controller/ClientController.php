<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\BackOfficeBundle\Entity\Client_;
use whiteLabel\BackOfficeBundle\Form\Client_Type;

/**
 * Class ClientController
 * @package whiteLabel\BackOfficeBundle\Controller
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
        $client = new Client_();
        $form = $this->createForm(Client_Type::class, $client);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $EM->persist($client);
            $EM->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le Client ' . $client->getClientInformation()->getNom() . ' a été créé avec succès.'
            );

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
        $form = $this->createForm(Client_Type::class, $client);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $EM->persist($client);
            $EM->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le Client ' . $client->getClientInformation()->getNom() . ' a bien été modifié.'
            );

            return $this->redirectToRoute('client_list', array());
        }

        return $this->render('whiteLabelBackOfficeBundle:Client:update.html.twig', array(
            'form'      => $form->createView(),
            'client'    => $client
        ));
    }
}