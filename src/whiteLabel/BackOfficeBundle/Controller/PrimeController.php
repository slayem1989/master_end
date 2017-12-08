<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use blackLabel\ImportBundle\Form\Import_primeType;

/**
 * Class PrimeController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class PrimeController extends Controller
{
    /**
     * @param $clientId
     * @return Response
     */
    public function listAction($clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $list = $repo->findByClient($clientId);

        return $this->render('whiteLabelBackOfficeBundle:Prime:list.html.twig', array(
            'list'      => $list,
            'clientId'  => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $primeId
     * @return Response
     */
    public function readAction($clientId, $primeId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $prime = $repo->find($primeId);

        /* /////////////////////////////////////////////////////////////////
                                GET LOT
        ///////////////////////////////////////////////////////////////// */
        $repo_lot = $EM->getRepository('blackLabelImportBundle:Import_lot');
        $lot = $repo_lot->findByPrime($clientId, $primeId);

        return $this->render('whiteLabelBackOfficeBundle:Prime:read.html.twig', array(
            'prime'     => $prime,
            'lot'       => $lot,
            'clientId'  => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @param $primeId
     * @return Response
     */
    public function updateAction(Request $request, $clientId, $primeId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $prime = $repo->find($primeId);

        // Format date to display
        $bdd_date = $prime->getDateCreation();
        $convert_date = $bdd_date->format('d/m/Y');
        $prime->setDate($convert_date);

        /* /////////////////////////////////////////////////////////////////
                                GET DATA FORM
        ///////////////////////////////////////////////////////////////// */
        $repo_lettreCheque = $EM->getRepository('whiteLabelBackOfficeBundle:LettreCheque');
        $list_lettreCheque = $repo_lettreCheque->findBy(array(
            'clientId' => $clientId
        ));

        foreach ($list_lettreCheque as $item) {
            $array_lettreCheque[$item->getNomModele()] = $item->getId() . ' | ' . $item->getNomModele();
        }
        $formOption[] = $array_lettreCheque;

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $form = $this->createForm(Import_primeType::class, $prime, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $prime->setDateModif(new \Datetime());
            $prime->setAuteurModif($_SESSION['login']->getUsername());

            // Format date to persist
            $post_date = $prime->getDate();
            $convert_date = \DateTime::createFromFormat('d/m/Y', $post_date);
            $prime->setDate($convert_date);

            $EM->persist($prime);
            $EM->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'La Prime ' . $prime->getNumero() . ' a bien été mise à jour.'
            );

            return $this->redirectToRoute('prime_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:Prime:update.html.twig', array(
            'form'      => $form->createView(),
            'prime'     => $prime,
            'clientId'  => $clientId
        ));
    }
}
