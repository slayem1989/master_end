<?php

namespace blackLabel\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use blackLabel\ImportBundle\Entity\Import_lot;
use blackLabel\ImportBundle\Form\Import_lotType;

/**
 * Class ImportController
 * @package blackLabel\ImportBundle\Controller
 */
class ImportController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importAction(Request $request)
    {
        $EM = $this->getDoctrine()->getManager();

        /* //////////////////////////////////////////////////////////
                                CREATE FORM
        /////////////////////////////////////////////////////////// */
        $formOption = array(
            $this->getParameter('client_total')
        );
        $lot = new Import_lot();
        $form = $this->createForm(Import_lotType::class, $lot, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $EM->persist($lot);
            $EM->flush();

            /* //////////////////////////////////////////////////////////
                                PERSIST DATA
            /////////////////////////////////////////////////////////// */
            $importService = $this->get('black_label.service.import');
            $importService->persistXLSX(
                $lot->getId(),
                $lot->getClient()
            );

            $request->getSession()->getFlashBag()->add(
                'success',
                'L\'import s\'est déroulé avec succès.'
            );

            return $this->redirectToRoute('bo_homepage', array());
        }

        return $this->render('blackLabelImportBundle:Import:import.html.twig', array(
            'form'  => $form->createView(),
        ));
    }
}
