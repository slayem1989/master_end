<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\BackOfficeBundle\Entity\Statut_lot;
use blackLabel\CommentaireBundle\Entity\Commentaire_lot;
use blackLabel\CommentaireBundle\Form\Commentaire_lotType;

/**
 * Class LotController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 */
class LotController extends Controller
{
    /**
     * @param Request $request
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_COORDINATEUR') or has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_ADMIN')")
     */
    public function listAction(Request $request, $clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF LOT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_lot');
        $list = $repo->findByClient($clientId);

        /* /////////////////////////////////////////////////////////////////
                                GET COMMENTAIRE
        ///////////////////////////////////////////////////////////////// */
        $commentaireRepository = $EM->getRepository('blackLabelCommentaireBundle:Commentaire_lot');
        $list_commentaireData = array();
        $list_commentaireForm = array();

        foreach ($list as $item) {
            if (!$request->request->has('blacklabel_commentairebundle_commentaire')) {

                $formOption = array();

                $lotId = $item['lotId'];
                $list_commentaireData[$lotId] = $commentaireRepository->findBy(array(
                    'lotId' => $lotId
                ));

                /* /////////////////////////////////////////////////////////////////
                                        GET FORM COMMENTAIRE
                ///////////////////////////////////////////////////////////////// */
                $formFactory = $this->get('form.factory');
                $commentaire = new Commentaire_lot();
                $commentaire->setLotId($lotId);

                $formCommentaire = $formFactory->createNamed(
                    'formCommentaire_' . $item['lotId'],
                    Commentaire_lotType::class,
                    $commentaire,
                    array('trait_choices' => $formOption)
                );
                $list_commentaireForm[$lotId] = $formCommentaire->createView();

                if ($request->isMethod('POST') && $formCommentaire->handleRequest($request)->isValid()) {
                    /* //////////////////////////////////////////////////////////
                                    PERSIST HISTORIQUE LOT
                    /////////////////////////////////////////////////////////// */
                    $historiqueService = $this->get('black_label.service.historique');
                    $historique = $historiqueService->saveLot(
                        $item['lotId'],
                        'Commentaire',
                        $commentaire->getContent(),
                        $item['lotStatutId']
                    );

                    $EM->persist($historique);
                    $EM->flush();
                    $EM->clear();

                    /* //////////////////////////////////////////////////////////
                                    PERSIST COMMENTAIRE LOT
                    /////////////////////////////////////////////////////////// */
                    $commentaire->setHistoriqueId($historique->getId());

                    $EM->persist($commentaire);
                    $EM->flush();
                    $EM->clear();

                    $request->getSession()->getFlashBag()->add(
                        'success',
                        'Le commentaire a été créé avec succès.'
                    );

                    return $this->redirectToRoute('lot_list', array(
                        'clientId' => $clientId
                    ));
                }

            }
        }

        return $this->render('whiteLabelBackOfficeBundle:Lot:list.html.twig', array(
            'list'                  => $list,
            'clientId'              => $clientId,
            'list_commentaireData'  => $list_commentaireData,
            'list_commentaireForm'  => $list_commentaireForm,
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_COORDINATEUR') or has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, $clientId, $lotId)
    {
        $EM = $this->getDoctrine()->getManager();
        $response = 99;

        /* /////////////////////////////////////////////////////////////////
                                GET LOT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_lot');
        $lotObject = $repo->find($lotId);
        $lotData = $repo->findByIdCustom($clientId, $lotId);

        /* /////////////////////////////////////////////////////////////////
                                GET UPDATE FORM
        ///////////////////////////////////////////////////////////////// */
        $lotService = $this->get('white_label.service.lot');
        $form_validate = $lotService->updateValidateType();

        /* //////////////////////////////////////////////////////////
                                GET HISTORIQUE SERVICE
        /////////////////////////////////////////////////////////// */
        $historiqueService = $this->get('black_label.service.historique');

        /* /////////////////////////////////////////////////////////////////
                                GET DELETE FORM
        ///////////////////////////////////////////////////////////////// */
        $form_delete = $this
            ->get('form.factory')
            ->create()
        ;

        if ($request->isMethod('POST') && $form_validate->handleRequest($request)->isValid()) {
            $lotObject->setDateModif(new \Datetime());
            $lotObject->setAuteurModif($_SESSION['login']->getUsername());

            // Format statut date to persist
            $post_dateStatut = $form_validate["date"]->getData();
            $format_dateStatut = \DateTime::createFromFormat('d/m/Y', $post_dateStatut);

            $statutCurrent = $lotObject->getStatutId();
            switch ($statutCurrent) {
                case Statut_lot::STATUT_1:
                    $lotObject->setStatutId(Statut_lot::STATUT_2);
                    $lotObject->setDateStatut2($format_dateStatut);

                    $historique = $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_2,
                        $_POST,
                        Statut_lot::STATUT_2,
                        $post_dateStatut
                    );
                    break;
                case Statut_lot::STATUT_2:
                    /* /////////////////////////////////////////////////////////////////
                                                GENERATE BAT
                    ///////////////////////////////////////////////////////////////// */
                    $lotService = $this->get('white_label.service.lot');
                    $response = $lotService->generateBAT($clientId, $lotId, $lotObject->getNumero());

                    if (true != $response) {
                        $historique = $historiqueService->saveLot(
                            $lotId,
                            Statut_lot::STATUT_SLUG_3.' => ERREUR: Le nombre de chèque restant est insuffisant.',
                            $_POST,
                            Statut_lot::STATUT_2
                        );
                    } else {
                        $lotObject->setStatutId(Statut_lot::STATUT_3);
                        $lotObject->setDateStatut3($format_dateStatut);

                        $historique = $historiqueService->saveLot(
                            $lotId,
                            Statut_lot::STATUT_SLUG_3,
                            $_POST,
                            Statut_lot::STATUT_3,
                            $post_dateStatut
                        );
                    }
                    break;
                case Statut_lot::STATUT_3:
                    $lotObject->setStatutId(Statut_lot::STATUT_4);
                    $lotObject->setDateStatut4($format_dateStatut);

                    $historique = $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_4,
                        $_POST,
                        Statut_lot::STATUT_4,
                        $post_dateStatut
                    );
                    break;
                case Statut_lot::STATUT_4:
                    $lotObject->setStatutId(Statut_lot::STATUT_5);
                    $lotObject->setDateStatut5($format_dateStatut);

                    $historique = $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_5,
                        $_POST,
                        Statut_lot::STATUT_5,
                        $post_dateStatut
                    );
                    break;
                case Statut_lot::STATUT_5:
                    $lotObject->setStatutId(Statut_lot::STATUT_6);
                    $lotObject->setDateStatut6($format_dateStatut);

                    $historique = $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_6,
                        $_POST,
                        Statut_lot::STATUT_6,
                        $post_dateStatut
                    );
                    break;
                case Statut_lot::STATUT_6:
                    $lotObject->setStatutId(Statut_lot::STATUT_7);
                    $lotObject->setDateStatut7($format_dateStatut);

                    $historique = $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_7,
                        $_POST,
                        Statut_lot::STATUT_7,
                        $post_dateStatut
                    );
                    break;
                case Statut_lot::STATUT_7:
                    $lotObject->setStatutId(Statut_lot::STATUT_8);
                    $lotObject->setDateStatut8($format_dateStatut);

                    $historique = $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_8,
                        $_POST,
                        Statut_lot::STATUT_8,
                        $post_dateStatut
                    );
                    break;
            }

            $EM->persist($lotObject);
            $EM->persist($historique);
            $EM->flush();
            $EM->clear();

            if (99 === $response) {
                $request->getSession()->getFlashBag()->add(
                    'success',
                    'Le lot n°' . $lotObject->getNumero() . ' a bien été mise à jour.'
                );
            } else {
                if (true != $response) {
                    $request->getSession()->getFlashBag()->add(
                        'danger',
                        'Le nombre de chèque restant est insuffisant.'
                    );
                } else {
                    $request->getSession()->getFlashBag()->add(
                        'success',
                        'Le lot n°' . $lotObject->getNumero() . ' a bien été mise à jour et les BAT ont été générés avec succès.'
                    );
                }
            }

            return $this->redirectToRoute('lot_list', array(
                'clientId' => $clientId
            ));
        }

        /* /////////////////////////////////////////////////////////////////
                                GET DENY FORM => ND STATUT 4
        ///////////////////////////////////////////////////////////////// */
        $form_deny4 = $lotService->updateDenyType(Statut_lot::STATUT_4);

        if ($request->isMethod('POST') && $form_deny4->handleRequest($request)->isValid()) {
            if ($request->request->has('form_deny4')) {
                // Format statut date to persist
                $post_dateStatut = $form_deny4["date"]->getData();
                $format_dateStatut = \DateTime::createFromFormat('d/m/Y', $post_dateStatut);

                $lotObject->setStatutId(Statut_lot::STATUT_1);
                $lotObject->setDateStatut2(null);
                $lotObject->setDateStatut3(null);
                $lotObject->setDateStatut44($format_dateStatut);

                $historique = $historiqueService->saveLot(
                    $lotId,
                    Statut_lot::STATUT_SLUG_44 . ' => Prochaine action: ' . Statut_lot::STATUT_SLUG_2,
                    $_POST,
                    Statut_lot::STATUT_44,
                    $post_dateStatut
                );

                $EM->persist($lotObject);
                $EM->persist($historique);
                $EM->flush();
                $EM->clear();

                $request->getSession()->getFlashBag()->add(
                    'success',
                    'Le lot n°' . $lotObject->getNumero() . ' a bien été mise à jour.'
                );

                return $this->redirectToRoute('lot_list', array(
                    'clientId' => $clientId
                ));
            }
        }

        /* /////////////////////////////////////////////////////////////////
                                GET DENY FORM => BAT STATUT 5
        ///////////////////////////////////////////////////////////////// */
        $form_deny5 = $lotService->updateDenyType(Statut_lot::STATUT_5);

        if ($request->isMethod('POST') && $form_deny5->handleRequest($request)->isValid()) {
            if ($request->request->has('form_deny5')) {
                // Format statut date to persist
                $post_dateStatut = $form_deny5["date"]->getData();
                $format_dateStatut = \DateTime::createFromFormat('d/m/Y', $post_dateStatut);

                $lotObject->setStatutId(Statut_lot::STATUT_2);
                $lotObject->setDateStatut3(null);
                $lotObject->setDateStatut4(null);
                $lotObject->setDateStatut55($format_dateStatut);

                $historique = $historiqueService->saveLot(
                    $lotId,
                    Statut_lot::STATUT_SLUG_55 . ' => Prochaine action: ' . Statut_lot::STATUT_SLUG_3,
                    $_POST,
                    Statut_lot::STATUT_55,
                    $post_dateStatut
                );

                $EM->persist($lotObject);
                $EM->persist($historique);
                $EM->flush();
                $EM->clear();

                $request->getSession()->getFlashBag()->add(
                    'success',
                    'Le Lot n° ' . $lotObject->getNumero() . ' a bien été mise à jour.'
                );

                return $this->redirectToRoute('lot_list', array(
                    'clientId' => $clientId
                ));
            }
        }

        return $this->render('whiteLabelBackOfficeBundle:Lot:update.html.twig', array(
            'form_delete'   => $form_delete->createView(),
            'form_validate' => $form_validate->createView(),
            'form_deny4'    => $form_deny4->createView(),
            'form_deny5'    => $form_deny5->createView(),
            'lot'           => $lotData,
            'clientId'      => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @param $lotId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_COORDINATEUR') or has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $clientId, $lotId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LOT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_lot');
        $lotObject = $repo->find($lotId);

        /* /////////////////////////////////////////////////////////////////
                                GET CANAL
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_canal');
        $canalArray = $repo->findBy(array(
            'lotId' => $lotId
        ));

        /* /////////////////////////////////////////////////////////////////
                                GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $primeArray = $repo->findBy(array(
            'canalId' => $canalArray[0]->getId()
        ));

        /* /////////////////////////////////////////////////////////////////
                                GET HISTORIQUE LOT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelHistoriqueBundle:Historique_lot');
        $historiqueArray = $repo->findBy(array(
            'lotId' => $lotId
        ));

        /* /////////////////////////////////////////////////////////////////
                                GET COMMENTAIRE LOT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelCommentaireBundle:Commentaire_lot');
        $commentaireArray = $repo->findBy(array(
            'lotId' => $lotId
        ));

        /* /////////////////////////////////////////////////////////////////
                                GET DELETE FORM
        ///////////////////////////////////////////////////////////////// */
        $form = $this
            ->get('form.factory')
            ->create()
        ;

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                // Declare PATH
                $folder_BAT = $this->getParameter('kernel.project_dir') . '/data/' . $clientId.'/BAT/';
                $file_import = $this->getParameter('kernel.project_dir') . '/data/' . $lotObject->file_getWebPath();

                foreach (scandir($folder_BAT) as $row) {
                    if ($row == '.' || $row == '..') {
                        continue;
                    } else {
                        if (stristr($row, $lotObject->getNumero()) === true) {
                            unlink($folder_BAT.$row);
                        }
                    }
                }
                if (file_exists($folder_BAT)) unlink($folder_BAT);
                if (file_exists($file_import)) unlink($file_import);
                foreach ($commentaireArray as $item) {$EM->remove($item);}
                foreach ($historiqueArray as $item) {$EM->remove($item);}
                foreach ($primeArray as $item) {$EM->remove($item);}
                $EM->remove($canalArray[0]);
                $EM->remove($lotObject);
                $EM->flush();
                $EM->clear();
            }

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le Lot n°' . $lotObject->getNumero() . ' a bien été supprimé.'
            );

            return $this->redirectToRoute('lot_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->redirectToRoute('lot_list', array(
            'clientId' => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $lotId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_COORDINATEUR') or has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_ADMIN')")
     */
    public function exportNoteDebitAction($clientId, $lotId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LOT
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_lot');
        $data = $repo->findDataNDByLot($clientId, $lotId);

        /* //////////////////////////////////////////////////////////////////////
                                GENERATE NOTE DE DEBIT
         //////////////////////////////////////////////////////////////////// */
        $lotService = $this->get('white_label.service.lot');
        $lotService->exportND($data);

        return $this->redirectToRoute('lot_list', array(
            'clientId' => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $lotNumero
     * @return Response
     * @Security("has_role('ROLE_COORDINATEUR') or has_role('ROLE_GESTIONNAIRE') or has_role('ROLE_ADMIN')")
     */
    public function exportBATAction($clientId, $lotNumero)
    {
        $pathFile = $this->getParameter('kernel.project_dir')
            . '/data/'.$clientId.'/BAT/BAT_'
            . $clientId . '_'
            . $lotNumero
            . '.pdf';

        // Download file
        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent(file_get_contents($pathFile));
        $response->headers->set('Content-Type', 'application/pdf; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . basename($pathFile));

        return $response;
    }
}
