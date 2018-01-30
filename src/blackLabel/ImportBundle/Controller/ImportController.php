<?php

namespace blackLabel\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\BackOfficeBundle\Entity\Statut_lot;
use whiteLabel\BackOfficeBundle\Entity\Statut_prime;
use blackLabel\ImportBundle\Entity\Import_lot;
use blackLabel\ImportBundle\Form\Import_lotType;

/**
 * Class ImportController
 * @package blackLabel\ImportBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
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
                            GET BANQUE BY CLIENT
        /////////////////////////////////////////////////////////// */
        $repo_client = $EM->getRepository('whiteLabelBackOfficeBundle:Client_');
        $dataClient = $repo_client->find($clientId);

        /* //////////////////////////////////////////////////////////
                                CREATE FORM
        /////////////////////////////////////////////////////////// */
        $formOption = array();
        $formOption[] = $clientId;
        $formOption[] = count($dataClient->getBanque());

        $lot = new Import_lot();
        $form = $this->createForm(Import_lotType::class, $lot, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $lot->setClientId($clientId);

            $EM->persist($lot);
            $EM->flush();
            $EM->clear();

            /* //////////////////////////////////////////////////////////
                                    PERSIST DATA
            /////////////////////////////////////////////////////////// */
            $importService = $this->get('black_label.service.import');
            $isValid = $importService->persistXLSX(
                $clientId,
                $lot->getId(),
                $lot->file_getWebPath(),
                $lot->getDateCreation()->format('d/m/Y'),
                $lot->getAuteurCreation()
            );

            /* //////////////////////////////////////////////////////////
                                PERSIST HISTORIQUE LOT
            /////////////////////////////////////////////////////////// */
            $historiqueService = $this->get('black_label.service.historique');
            if (true == $isValid) {
                $historiqueLot = $historiqueService->saveLot(
                    $lot->getId(),
                    Statut_lot::STATUT_SLUG_1,
                    $_POST,
                    $lot->getStatutId()
                );
                $EM->persist($historiqueLot);

                /* /////////////////////////////////////////////////////////////////
                                            GET CANAL
                ///////////////////////////////////////////////////////////////// */
                $repo_canal = $EM->getRepository('blackLabelImportBundle:Import_canal');
                $dataCanal = $repo_canal->findOneBy(array(
                    'lotId' => $lot->getId()
                ));

                /* /////////////////////////////////////////////////////////////////
                                            GET PRIME
                ///////////////////////////////////////////////////////////////// */
                $repo_prime = $EM->getRepository('blackLabelImportBundle:Import_prime');
                $dataPrime = $repo_prime->findBy(array(
                    'canalId' => $dataCanal->getId()
                ));

                /* //////////////////////////////////////////////////////////
                                    PERSIST HISTORIQUE PRIME
                /////////////////////////////////////////////////////////// */
                foreach ($dataPrime as $item) {
                    $historiquePrime = $historiqueService->savePrime(
                        $item->getId(),
                        Statut_prime::STATUT_SLUG_1,
                        '',
                        $item->getStatutId()
                    );
                    $EM->persist($historiquePrime);
                }


                $request->getSession()->getFlashBag()->add(
                    'success',
                    'L\'import du Lot ' . $lot->getNumero()  . ' s\'est déroulé avec succès.'
                );
            } else {
                $importService->updateStatutLot($lot->getId());

                /* //////////////////////////////////////////////////////////
                               PERSIST HISTORIQUE LOT
                /////////////////////////////////////////////////////////// */
                $historiqueLot = $historiqueService->saveLot(
                    $lot->getId(),
                    Statut_lot::STATUT_SLUG_11,
                    $_POST,
                    Statut_lot::STATUT_11
                );
                $EM->persist($historiqueLot);

                $request->getSession()->getFlashBag()->add(
                    'danger',
                    'L\'import du Lot ne s\'est pas déroulé correctement.'
                );
            }

            $EM->flush();
            $EM->clear();

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
