<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
        $lot = $repo->find($lotId);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        /*
        $form = $this->createForm(Import_lotType::class, $lot);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

        }
        */

        return $this->render('whiteLabelBackOfficeBundle:Lot:update.html.twig', array(
            'lot'       => $lot,
            'clientId'  => $clientId
        ));
    }
}
