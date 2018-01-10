<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use blackLabel\ImportBundle\Form\Import_primeType;
use blackLabel\CommentaireBundle\Entity\Commentaire_prime;
use blackLabel\CommentaireBundle\Form\Commentaire_primeType;

use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function listAction(Request $request, $clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET LIST OF PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $list = $repo->findByClient($clientId);

        /* /////////////////////////////////////////////////////////////////
                                GET COMMENTAIRE FORM
        ///////////////////////////////////////////////////////////////// */
        $commentaire = new Commentaire_prime();
        $form = $this->createForm(Commentaire_primeType::class, $commentaire);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $post_primeId = $form["prime_id"]->getData();
            $post_content = $form["content"]->getData();

            /* //////////////////////////////////////////////////////////
                            PERSIST HISTORIQUE PRIME
            /////////////////////////////////////////////////////////// */
            $repo_prime = $EM->getRepository('blackLabelImportBundle:Import_prime');
            $objectPrime = $repo_prime->find($post_primeId);

            $historiqueService = $this->get('black_label.service.historique');
            $historiqueId = $historiqueService->savePrimeByCommentaire(
                $objectPrime->getId(),
                'Commentaire',
                $post_content,
                $objectPrime->getStatutId()
            );

            /* //////////////////////////////////////////////////////////
                            PERSIST COMMENTAIRE PRIME
            /////////////////////////////////////////////////////////// */
            $commentaire->setHistoriqueId($historiqueId);
            $commentaire->setPrimeId($post_primeId);
            $commentaire->setContent($post_content);

            $EM->persist($commentaire);
            $EM->flush();
            $EM->clear();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le commentaire a été créé avec succès.'
            );

            return $this->redirectToRoute('prime_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:Prime:list.html.twig', array(
            'list'      => $list,
            'clientId'  => $clientId,
            'form'      => $form->createView()
        ));
    }

    /**
     * @param $clientId
     * @return bool|JsonResponse
     */
    public function listAjaxAction($clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        if (!empty($_POST) ) {
            $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
            $recordsTotal = $repo->countByClient($clientId)['countId'];



            /* START of $_POST variables coming from datatable */
            $draw = $_POST["draw"]; //Counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence
            $orderByColumnIndex  = $_POST['order'][0]['column']; //Index of the sorting column (0 index based)
            $orderBy = $_POST['columns'][$orderByColumnIndex]['data']; //Get name of the sorting column from its index
            $orderType = $_POST['order'][0]['dir']; //ASC or DESC
            $start  = $_POST["start"]; //Paging first record indicator
            $length = $_POST['length']; //Number of records that the table can display in the current draw
            /* END of $_POST variables */



            /* START INIT of column search */
            $columnWhereTmp = array();
            for ($i=0; $i<count($_POST['columns']); $i++) {
                if ('' != ($_POST['columns'][$i]['search']['value'])) {
                    $columnWhereTmp[] = $_POST['columns'][$i]['search']['value'];
                }
            }
            /* END INIT of column search */



            /* START of search */
            if (!empty($_POST['search']['value'])) {

                $dataColumn = array(
                    'lotNumero'             => 'il.numero',
                    'primeNumero'           => 'ip.numero',
                    'primeType'             => 'ip.type',
                    'primeEmail'            => 'ip.email',
                    'primeTelephone'        => 'ip.telephone',
                    'primeStatut'           => 'sp.slug',
                    'primeDateIntegration'  => 'ip.date',
                    'primeOnglet'           => 'ip.onglet'
                );

                for ($i=0; $i<count($_POST['columns']); $i++) {
                    $globalSearch = $_POST['columns'][$i]['data'];
                    if ('action' != $globalSearch) {
                        if ('primeIdentifiant' == $globalSearch) {
                            $where[] =  "ip.nom LIKE '%" . $_POST['search']['value'] . "%'" .
                                        " OR " .
                                        "ip.prenom LIKE '%" . $_POST['search']['value'] . "%'" .
                                        " OR " .
                                        "ip.siren LIKE '%" . $_POST['search']['value'] . "%'" .
                                        " OR " .
                                        "ip.denomination LIKE '%" . $_POST['search']['value'] . "%'" .
                                        " OR " .
                                        "ip.representant LIKE '%" . $_POST['search']['value'] . "%'"
                            ;
                        } elseif ('primeVille' == $globalSearch) {
                            $where[] =  "ip.code_postal_facturation LIKE '%" . $_POST['search']['value'] . "%'" .
                                        " OR " .
                                        "ip.ville_facturation LIKE '%" . $_POST['search']['value'] . "%'"
                            ;
                        } else {
                            $where[] = "$dataColumn[$globalSearch] LIKE '%" . $_POST['search']['value'] . "%'";
                        }
                    }
                }
                $copy = implode(" OR ", $where);
                $where = " AND " . $copy;

                // Search filtered result with limit and orderBy clauses
                $data = $repo->findAjaxByClient($clientId, $orderBy, $orderType, $start, $length, $where);

                $recordsFiltered = $repo->countByClient($clientId, $where)['countId'];

            } elseif (!empty($columnWhereTmp)) {

                for ($i=0; $i<count($_POST['columns']); $i++) {
                    if ('' != ($_POST['columns'][$i]['search']['value'])) {
                        $columnSearch = $_POST['columns'][$i]['data'];
                        switch ($columnSearch) {
                            case 'lotNumero':
                                $columnWhere[] =  "il.numero LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                            case 'primeNumero':
                                $columnWhere[] =  "ip.numero LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                            case 'primeIdentifiant':
                                $columnWhere[] =   "ip.nom LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'" .
                                    " OR " .
                                    "ip.prenom LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'" .
                                    " OR " .
                                    "ip.siren LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'" .
                                    " OR " .
                                    "ip.denomination LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'" .
                                    " OR " .
                                    "ip.representant LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'"
                                ;
                                break;
                            case 'primeVille':
                                $columnWhere[] =   "ip.code_postal_facturation LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'" .
                                    " OR " .
                                    "ip.ville_facturation LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'"
                                ;
                                break;
                            case 'primeType':
                                $columnWhere[] =  "ip.type LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                            case 'primeEmail':
                                $columnWhere[] =  "ip.email LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                            case 'primeTelephone':
                                $columnWhere[] =  "ip.telephone LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                            case 'primeStatut':
                                $columnWhere[] =  "sp.slug LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                            case 'primeDateIntegration':
                                $columnWhere[] =  "ip.date LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                            case 'primeOnglet':
                                $columnWhere[] =  "ip.onglet LIKE '%" . $_POST['columns'][$i]['search']['value'] . "%'";
                                break;
                        }
                    }
                }
                if (!empty($columnWhere)) {
                    $columnCopy = implode(" AND ", $columnWhere);
                    $columnWhere = " AND " . $columnCopy;
                } else {
                    $columnWhere = "";
                }

                // Search filtered result with limit and orderBy clauses
                $data = $repo->findAjaxByClient($clientId, $orderBy, $orderType, $start, $length, $columnWhere);

                $recordsFiltered = $repo->countByClient($clientId, $columnWhere)['countId'];

            } else {

                // Search all result with limit and orderBy clauses
                $data = $repo->findAjaxByClient($clientId, $orderBy, $orderType, $start, $length);

                $recordsFiltered = $recordsTotal;

            }
            /* END of search */

            $response = array(
                "draw"              => intval($draw),
                "recordsTotal"      => $recordsTotal,
                "recordsFiltered"   => $recordsFiltered,
                "data"              => $data
            );

            $json = new JsonResponse();
            $json->setData($response);

            return $json;
        } else {
            return false;
        }
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
        $bdd_date = $prime->getDate();
        $convert_date = $bdd_date->format('d/m/Y');
        $prime->setDate($convert_date);

        /* /////////////////////////////////////////////////////////////////
                                GET DATA FORM
        ///////////////////////////////////////////////////////////////// */
        $repo_lettreCheque = $EM->getRepository('whiteLabelBackOfficeBundle:LettreCheque');
        $list_lettreCheque = $repo_lettreCheque->findBy(array(
            'clientId' => $clientId
        ));

        $array_lettreCheque = array();
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

            if ($prime->getNumero()) {
                $chequeService= $this->get('white_label.service.cheque');
                $prime->setNumero($chequeService->formatNumeroCheque($prime->getNumero()));
            }

            $EM->persist($prime);
            $EM->flush();
            $EM->clear();

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

    /**
     * @param Request $request
     * @param $clientId
     * @param $primeId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateRIBAction(Request $request, $clientId, $primeId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $prime = $repo->find($primeId);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $form = $this->createForm(Import_primeType::class, $prime);
        $form->remove('type');
        $form->remove('date');
        $form->remove('index');
        $form->remove('numero');
        $form->remove('adresseFacturation');
        $form->remove('complementFacturation');
        $form->remove('codePostalFacturation');
        $form->remove('villeFacturation');
        $form->remove('paysFacturation');
        $form->remove('adresseChantier');
        $form->remove('complementChantier');
        $form->remove('codePostalChantier');
        $form->remove('villeChantier');
        $form->remove('telephone');
        $form->remove('email');
        $form->remove('montantAide');
        $form->remove('numeroAction');
        $form->remove('apporteurAffaire');
        $form->remove('onglet');
        $form->remove('nomModele');

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $prime->setDateModif(new \Datetime());
            $prime->setAuteurModif($_SESSION['login']->getUsername());

            $EM->persist($prime);
            $EM->flush();
            $EM->clear();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le RIB de la Prime ' . $prime->getNumero() . ' a bien été mis à jour.'
            );

            return $this->redirectToRoute('prime_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:Prime:update_rib.html.twig', array(
            'form'      => $form->createView(),
            'prime'     => $prime,
            'clientId'  => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @param $primeId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateAddressAction(Request $request, $clientId, $primeId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $prime = $repo->find($primeId);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $form = $this->createForm(Import_primeType::class, $prime);
        $form->remove('type');
        $form->remove('date');
        $form->remove('index');
        $form->remove('numero');
        $form->remove('paysFacturation');
        $form->remove('adresseChantier');
        $form->remove('complementChantier');
        $form->remove('codePostalChantier');
        $form->remove('villeChantier');
        $form->remove('telephone');
        $form->remove('email');
        $form->remove('iban');
        $form->remove('montantAide');
        $form->remove('numeroAction');
        $form->remove('apporteurAffaire');
        $form->remove('onglet');
        $form->remove('nomModele');

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $prime->setDateModif(new \Datetime());
            $prime->setAuteurModif($_SESSION['login']->getUsername());

            $EM->persist($prime);
            $EM->flush();
            $EM->clear();

            $request->getSession()->getFlashBag()->add(
                'success',
                'L\'adresse de la Prime ' . $prime->getNumero() . ' a bien été mise à jour.'
            );

            return $this->redirectToRoute('prime_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:Prime:update_address.html.twig', array(
            'form'      => $form->createView(),
            'prime'     => $prime,
            'clientId'  => $clientId
        ));
    }
}
