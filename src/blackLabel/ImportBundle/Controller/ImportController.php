<?php

namespace blackLabel\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use blackLabel\HistoriqueBundle\Entity\Historique;
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
    public function importAction(Request $request, $clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* //////////////////////////////////////////////////////////
                                CREATE FORM
        /////////////////////////////////////////////////////////// */
        $formOption = array($clientId);
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
                $lot->file_getWebPath()
            );

            /* //////////////////////////////////////////////////////////
                                    PERSIST HISTORIQUE
            /////////////////////////////////////////////////////////// */
            $historiqueService = $this->get('black_label.service.historique');
            $historiqueService->save(
                $lot->getId(),
                Historique::STATUT_1,
                $_POST,
                $lot->getStatutId()
            );

            $request->getSession()->getFlashBag()->add(
                'success',
                'L\'import du Lot ' . $lot->getNumero()  . ' s\'est déroulé avec succès.'
            );

            return $this->redirectToRoute('lot_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('blackLabelImportBundle:Import:import.html.twig', array(
            'form'      => $form->createView(),
            'clientId'  => $clientId
        ));
    }
}
