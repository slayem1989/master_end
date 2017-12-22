<?php

namespace whiteLabel\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use whiteLabel\BackOfficeBundle\Entity\Cheque_stock;
use whiteLabel\BackOfficeBundle\Form\Cheque_stockType;

/**
 * Class ChequeController
 * @package whiteLabel\BackOfficeBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class ChequeController extends Controller
{
    /**
     * @param $clientId
     * @return Response
     */
    public function listStockAction($clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                LIST OF STOCK CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Cheque_stock');
        $listStock = $repo->findByClient($clientId);

        return $this->render('whiteLabelBackOfficeBundle:Cheque:Stock/list.html.twig', array(
            'list'      => $listStock,
            'clientId'  => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createStockAction(Request $request, $clientId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $formOption = array();
        $formOption[] = $clientId;
        $formOption[] = null;

        $objectStock = new Cheque_stock();
        $form = $this->createForm(Cheque_stockType::class, $objectStock, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $objectStock->setClientId($clientId);

            // Format reception date to persist
            $post_dateReception = $objectStock->getDateReception();
            if (null !== $post_dateReception) $convert_dateReception = \DateTime::createFromFormat('d/m/Y', $post_dateReception);
            else $convert_dateReception = new \DateTime($this->getParameter('date_reference'));
            $objectStock->setDateReception($convert_dateReception);

            // Format numero chèque
            $chequeService= $this->get('white_label.service.cheque');
            $objectStock->setFirst($chequeService->formatNumeroCheque($objectStock->getFirst()));
            $objectStock->setLast($chequeService->formatNumeroCheque($objectStock->getLast()));

            /* //////////////////////////////////////////////////////////
                                PERSIST CHEQUE ITEM
            /////////////////////////////////////////////////////////// */
            $chequeService = $this->get('white_label.service.cheque');
            $listCheque = $chequeService->createChequeItem(
                $objectStock->getFirst(),
                $objectStock->getLast()
            );
            foreach ($listCheque as $item) {
                $objectStock->addCheque($item);
            }

            $EM->persist($objectStock);
            $EM->flush();

            /* //////////////////////////////////////////////////////////
                                UPDATE CHEQUE ITEM
            /////////////////////////////////////////////////////////// */
            $chequeService->updateStockId($listCheque, $objectStock->getId());

            $request->getSession()->getFlashBag()->add(
                'success',
                'L\'import du stock Chèque n° ' . $objectStock->getReferenceBoite() . ' a été effectué avec succès.'
            );

            return $this->redirectToRoute('chequeStock_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:Cheque:Stock/create.html.twig', array(
            'form'      => $form->createView(),
            'clientId'  => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $stockId
     * @return Response
     */
    public function readStockAction($clientId, $stockId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                    GET STOCK
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Cheque_stock');
        $objectStock = $repo->findByStock($stockId);

        return $this->render('whiteLabelBackOfficeBundle:Cheque:Stock/read.html.twig', array(
            'stock'     => $objectStock,
            'clientId'  => $clientId
        ));
    }

    /**
     * @param Request $request
     * @param $clientId
     * @param $stockId
     * @return Response
     */
    public function updateStockAction(Request $request, $clientId, $stockId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET STOCK
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Cheque_stock');
        $objectStock = $repo->find($stockId);

        // Format Date Reception to display
        $bdd_date = $objectStock->getDateReception();
        $convert_date = $bdd_date->format('d/m/Y');
        $objectStock->setDateReception($convert_date);

        // Format Integer to display
        $chequeFirst = (int)$objectStock->getFirst();
        $chequeLast = (int)$objectStock->getLast();
        $objectStock->setFirst($chequeFirst);
        $objectStock->setLast($chequeLast);

        /* /////////////////////////////////////////////////////////////////
                                BUILD FORM
        ///////////////////////////////////////////////////////////////// */
        $formOption = array();
        $formOption[] = $clientId;
        $formOption[] = $objectStock->getBanqueId();

        $form = $this->createForm(Cheque_stockType::class, $objectStock, array(
            'trait_choices' => $formOption
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $objectStock->setDateModif(new \Datetime());
            $objectStock->setAuteurModif($_SESSION['login']->getUsername());

            // Format reception date to persist
            $post_dateReception = $objectStock->getDateReception();
            if (null !== $post_dateReception) $convert_dateReception = \DateTime::createFromFormat('d/m/Y', $post_dateReception);
            else $convert_dateReception = new \DateTime($this->getParameter('date_reference'));
            $objectStock->setDateReception($convert_dateReception);

            // Format numero chèque
            $chequeService= $this->get('white_label.service.cheque');
            $objectStock->setFirst($chequeService->formatNumeroCheque($objectStock->getFirst()));
            $objectStock->setLast($chequeService->formatNumeroCheque($objectStock->getLast()));

            $EM->persist($objectStock);
            $EM->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Le stock Chèque n° ' . $objectStock->getReferenceBoite() . ' a bien été mis à jour.'
            );

            return $this->redirectToRoute('chequeStock_list', array(
                'clientId' => $clientId
            ));
        }

        return $this->render('whiteLabelBackOfficeBundle:Cheque:Stock/update.html.twig', array(
            'form'      => $form->createView(),
            'stock'     => $objectStock,
            'clientId'  => $clientId
        ));
    }

    /**
     * @param $clientId
     * @param $stockId
     * @return Response
     */
    public function listChequeAction($clientId, $stockId)
    {
        $EM = $this->getDoctrine()->getManager();

        /* /////////////////////////////////////////////////////////////////
                                LIST OF CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:Cheque_item');
        if (null != $stockId) {
            $listCheque = $repo->findByStock($clientId, $stockId);
        } else {
            $listCheque = $repo->findByClient($clientId);
        }

        return $this->render('whiteLabelBackOfficeBundle:Cheque:Item/list.html.twig', array(
            'list'      => $listCheque,
            'clientId'  => $clientId
        ));
    }
}
