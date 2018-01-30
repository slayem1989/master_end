<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\BackOfficeBundle\Entity\ModeleLettre;
use whiteLabel\BackOfficeBundle\Form\ModeleLettreType;

/**
 * Class ModeleLettreController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class ModeleLettreController extends Controller
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
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:ModeleLettre');
        $list = $repo->findByClient($clientId);

        return $this->render('whiteLabelBackOfficeBundle:ModeleLettre:list.html.twig', array(
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
        $formOption = array();
        $formOption[] = true;
        $modeleLettre = new ModeleLettre();
        $form = $this->createForm(ModeleLettreType::class, $modeleLettre, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $modeleLettre->setClientId($clientId);

            $EM->persist($modeleLettre);
            $EM->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le Modèle de Lettre ' . $modeleLettre->getNom() . ' a été créé avec succès.'
            );

            return $this->redirectToRoute('modeleLettre_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:ModeleLettre:create.html.twig', array(
            'form'          => $form->createView(),
            'modeleLettre'  => $modeleLettre,
            'clientId'      => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $modeleLettreId
     * @return Response
     */
    public function readAction($clientId, $modeleLettreId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LETTRE CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:ModeleLettre');
        $modeleLettre = $repo->find($modeleLettreId);

        return $this->render('whiteLabelBackOfficeBundle:ModeleLettre:read.html.twig', array(
            'modeleLettre'  => $modeleLettre,
            'clientId'      => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @param $modeleLettreId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateAction(Request $request, $clientId, $modeleLettreId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LETTRE CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:ModeleLettre');
        $modeleLettre = $repo->find($modeleLettreId);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $formOption = array();
        $formOption[] = false;
        $form = $this->createForm(ModeleLettreType::class, $modeleLettre, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $modeleLettre->setDateModif(new \Datetime());
            $modeleLettre->setAuteurModif($_SESSION['login']->getUsername());

            $EM->persist($modeleLettre);
            $EM->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le Modèle de Lettre ' . $modeleLettre->getNom() . ' a bien été mis à jour.'
            );

            return $this->redirectToRoute('modeleLettre_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:ModeleLettre:update.html.twig', array(
            'form'          => $form->createView(),
            'modeleLettre'  => $modeleLettre,
            'clientId'      => $clientId
        ));
    }
}
