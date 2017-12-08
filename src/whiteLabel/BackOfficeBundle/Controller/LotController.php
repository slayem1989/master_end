<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use blackLabel\HistoriqueBundle\Entity\Historique;
use blackLabel\CommentaireBundle\Entity\Commentaire;
use blackLabel\CommentaireBundle\Form\CommentaireType;

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
        $commentaireRepository = $EM->getRepository('blackLabelCommentaireBundle:Commentaire');
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
                $commentaire = new Commentaire();
                $commentaire->setLotId($lotId);

                $formCommentaire = $formFactory->createNamed(
                    'formCommentaire_' . $item['lotId'],
                    CommentaireType::class,
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

                    $historiqueService->save(
                        $lotId,
                        Historique::STATUT_2,
                        $_POST,
                        2,
                        $post_dateStatut
                    );
                    break;
                case 2:
                    $lotObject->setStatutId(3);
                    $lotObject->setDateStatut3($format_dateStatut);

                    $historiqueService->save(
                        $lotId,
                        Historique::STATUT_3,
                        $_POST,
                        3,
                        $post_dateStatut
                    );
                    break;
                case 3:
                    $lotObject->setStatutId(4);
                    $lotObject->setDateStatut4($format_dateStatut);

                    $historiqueService->save(
                        $lotId,
                        Historique::STATUT_4,
                        $_POST,
                        4,
                        $post_dateStatut
                    );
                    break;
                case 4:
                    $lotObject->setStatutId(5);
                    $lotObject->setDateStatut5($format_dateStatut);

                    $historiqueService->save(
                        $lotId,
                        Historique::STATUT_5,
                        $_POST,
                        5,
                        $post_dateStatut
                    );
                    break;
                case 5:
                    $lotObject->setStatutId(6);
                    $lotObject->setDateStatut6($format_dateStatut);

                    $historiqueService->save(
                        $lotId,
                        Historique::STATUT_6,
                        $_POST,
                        6,
                        $post_dateStatut
                    );
                    break;
                case 6:
                    $lotObject->setStatutId(7);
                    $lotObject->setDateStatut7($format_dateStatut);

                    $historiqueService->save(
                        $lotId,
                        Historique::STATUT_7,
                        $_POST,
                        7,
                        $post_dateStatut
                    );
                    break;
                case 7:
                    $lotObject->setStatutId(8);
                    $lotObject->setDateStatut8($format_dateStatut);

                    $historiqueService->save(
                        $lotId,
                        Historique::STATUT_8,
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

                $historiqueService->save(
                    $lotId,
                    Historique::STATUT_44 . ' => Prochaine action: ' . Historique::STATUT_2,
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

                $historiqueService->save(
                    $lotId,
                    Historique::STATUT_55 . ' => Prochaine action: ' . Historique::STATUT_3,
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
}
