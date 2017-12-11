<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\BackOfficeBundle\Entity\Statut_lot;
use blackLabel\CommentaireBundle\Entity\Commentaire_lot;
use blackLabel\CommentaireBundle\Form\Commentaire_lotType;

use Spipu\Html2Pdf\Html2Pdf;

/**
 * Class LotController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class LotController extends Controller
{
    /**
     * @param Request $request
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\Response
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
                    'lot_id' => $lotId
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
                    $EM->persist($commentaire);
                    $EM->flush();

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
     */
    public function updateAction(Request $request, $clientId, $lotId)
    {
        $EM = $this->getDoctrine()->getManager();

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
                                PERSIST HISTORIQUE
        /////////////////////////////////////////////////////////// */
        $historiqueService = $this->get('black_label.service.historique');

        if ($request->isMethod('POST') && $form_validate->handleRequest($request)->isValid()) {
            $lotObject->setDateModif(new \Datetime());
            $lotObject->setAuteurModif($_SESSION['login']->getUsername());

            // Format statut date to persist
            $post_dateStatut = $form_validate["date"]->getData();
            $format_dateStatut = \DateTime::createFromFormat('d/m/Y', $post_dateStatut);

            $statutCurrent = $lotObject->getStatutId();
            switch ($statutCurrent) {
                case 1:
                    $lotObject->setStatutId(2);
                    $lotObject->setDateStatut2($format_dateStatut);

                    $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_2,
                        $_POST,
                        2,
                        $post_dateStatut
                    );
                    break;
                case 2:
                    $lotObject->setStatutId(3);
                    $lotObject->setDateStatut3($format_dateStatut);

                    $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_3,
                        $_POST,
                        3,
                        $post_dateStatut
                    );
                    break;
                case 3:
                    $lotObject->setStatutId(4);
                    $lotObject->setDateStatut4($format_dateStatut);

                    $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_4,
                        $_POST,
                        4,
                        $post_dateStatut
                    );
                    break;
                case 4:
                    $lotObject->setStatutId(5);
                    $lotObject->setDateStatut5($format_dateStatut);

                    $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_5,
                        $_POST,
                        5,
                        $post_dateStatut
                    );
                    break;
                case 5:
                    $lotObject->setStatutId(6);
                    $lotObject->setDateStatut6($format_dateStatut);

                    $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_6,
                        $_POST,
                        6,
                        $post_dateStatut
                    );
                    break;
                case 6:
                    $lotObject->setStatutId(7);
                    $lotObject->setDateStatut7($format_dateStatut);

                    $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_7,
                        $_POST,
                        7,
                        $post_dateStatut
                    );
                    break;
                case 7:
                    $lotObject->setStatutId(8);
                    $lotObject->setDateStatut8($format_dateStatut);

                    $historiqueService->saveLot(
                        $lotId,
                        Statut_lot::STATUT_SLUG_8,
                        $_POST,
                        8,
                        $post_dateStatut
                    );
                    break;
            }
            $EM->persist($lotObject);
            $EM->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le lot n°' . $lotObject->getNumero() . ' a bien été mise à jour.'
            );

            return $this->redirectToRoute('lot_list', array(
                'clientId' => $clientId
            ));
        }

        /* /////////////////////////////////////////////////////////////////
                                GET DENY FORM => ND STATUT 4
        ///////////////////////////////////////////////////////////////// */
        $form_deny4 = $lotService->updateDenyType(4);

        if ($request->isMethod('POST') && $form_deny4->handleRequest($request)->isValid()) {
            if ($request->request->has('form_deny4')) {
                $lotObject->setStatutId(1);
                $lotObject->setDateStatut2(null);
                $lotObject->setDateStatut3(null);
                $lotObject->setDateStatut44(new \Datetime());

                $EM->persist($lotObject);
                $EM->flush();

                $historiqueService->saveLot(
                    $lotId,
                    Statut_lot::STATUT_SLUG_44 . ' => Prochaine action: ' . Statut_lot::STATUT_SLUG_2,
                    $_POST,
                    44
                );

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
        $form_deny5 = $lotService->updateDenyType(5);

        if ($request->isMethod('POST') && $form_deny5->handleRequest($request)->isValid()) {
            if ($request->request->has('form_deny5')) {
                $lotObject->setStatutId(2);
                $lotObject->setDateStatut3(null);
                $lotObject->setDateStatut4(null);
                $lotObject->setDateStatut55(new \Datetime());

                $EM->persist($lotObject);
                $EM->flush();

                $historiqueService->saveLot(
                    $lotId,
                    Statut_lot::STATUT_SLUG_55 . ' => Prochaine action: ' . Statut_lot::STATUT_SLUG_3,
                    $_POST,
                    55
                );

                $request->getSession()->getFlashBag()->add(
                    'success',
                    'Le lot n°' . $lotObject->getNumero() . ' a bien été mise à jour.'
                );

                return $this->redirectToRoute('lot_list', array(
                    'clientId' => $clientId
                ));
            }
        }

        return $this->render('whiteLabelBackOfficeBundle:Lot:update.html.twig', array(
            'form_validate' => $form_validate->createView(),
            'form_deny4'    => $form_deny4->createView(),
            'form_deny5'    => $form_deny5->createView(),
            'lot'           => $lotData,
            'clientId'      => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $lotId
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
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
        $tva = $this->getParameter('tva');
        $template = $this->renderView('whiteLabelBackOfficeBundle:Lot:inc/export/note_debit.html.twig', array(
            'list_canal'    => $data,
            'TVA'           => $tva
        ));

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        //$html2pdf->pdf->SetDisplayMode('fullpage');
        //$html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($template);
        $html2pdf->Output($data[0]['lotNumero'] . '_note_debit_' . date('dmY') . '.pdf');
    }
}
