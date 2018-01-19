<?php

namespace whiteLabel\BackOfficeBundle\Service;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use blackLabel\GenericBundle\Entity\Nuts;
use Spipu\Html2Pdf\Html2Pdf;

use Unoconv\Unoconv as Unoconv;
use Symfony\Component\Process\Process;

/**
 * Class LotService
 * @package whiteLabel\BackOfficeBundle\Service
 */
class LotService
{
    /**
     * @var string
     */
    private $doctrine;

    /**
     * @var string
     */
    private $container;

    /**
     * @var string
     */
    private $formFactory;


    /**
     * LotService constructor.
     * @param $doctrine
     * @param $container
     */
    public function __construct(
        $doctrine,
        $container
    ) {
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->formFactory = $container->get('form.factory');
    }



    /* *****************************************************************
    ********************************************************************
                                F O R M
    ********************************************************************
    *******************************************************************/
    /**
     * @return mixed
     */
    public function updateValidateType()
    {
        $now = new \Datetime();
        $form = $this->formFactory->createBuilder()
            ->add('date',       TextType::class,    array(
                                                        'required'  => true,
                                                        'label'     => 'Date de validation',
                                                        'attr'      => array(
                                                            'placeholder' => 'DD/MM/YYYY',
                                                        ),
                                                        'data'      => $now->format('d/m/Y')
                                                    ))
            ->add('valider',    SubmitType::class)
            ->getForm()
        ;

        return $form;
    }

    /**
     * @param $statutId
     * @return mixed
     */
    public function updateDenyType($statutId)
    {
        $now = new \Datetime();
        $form = $this->formFactory->createNamedBuilder('form_deny'.$statutId)
            ->add('date',       TextType::class,    array(
                                                        'required'  => true,
                                                        'label'     => 'Date de refus',
                                                        'attr'      => array(
                                                            'placeholder' => 'DD/MM/YYYY',
                                                        ),
                                                        'data'      => $now->format('d/m/Y')
                                                    ))
            ->add('refuser',    SubmitType::class)
            ->getForm()
        ;

        return $form;
    }

    /**
     * @param $data
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     */
    public function exportND($data)
    {
        /* //////////////////////////////////////////////////////////////////////
                                GENERATE TEMPLATE
         //////////////////////////////////////////////////////////////////// */
        $template = $this->container->get('templating')->render('whiteLabelBackOfficeBundle:Lot:inc/export/note_debit.html.twig', array(
            'list_canal'    => $data,
            'TVA'           => $this->container->getParameter('tva')
        ));

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        //$html2pdf->pdf->SetDisplayMode('fullpage');
        //$html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($template);
        $html2pdf->Output($data[0]['lotNumero'] . '_note_debit_' . date('dmY') . '.pdf');
    }

    /**
     * @param $clientId
     * @param $lotId
     * @param $listPrime
     * @return bool
     */
    public function generateBAT($clientId, $lotId, $listPrime)
    {
        set_time_limit(0);
        $EM = $this->doctrine->getManager();
        $TBS = $this->container->get('opentbs');
        $repo_prime = $EM->getRepository('blackLabelImportBundle:Import_prime');

        /* /////////////////////////////////////////////////////////////////
                                    GET LOT
        ///////////////////////////////////////////////////////////////// */
        $repo_lot = $EM->getRepository('blackLabelImportBundle:Import_lot');
        $lot = $repo_lot->find($lotId);

        /* /////////////////////////////////////////////////////////////////
                                    GET CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo_chequeItem = $EM->getRepository('whiteLabelBackOfficeBundle:Cheque_item');
        $listChequeItem = $repo_chequeItem->findForBATByClient($clientId, $lotId);

        /* /////////////////////////////////////////////////////////////////
                                GET LETTRE CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo_lettreCheque = $EM->getRepository('whiteLabelBackOfficeBundle:LettreCheque');
        $listLettreCheque = $repo_lettreCheque->findBy(array(
            'clientId' => $clientId
        ));
        $arraySourceFile = array();
        foreach ($listLettreCheque as $item) {
            $arraySourceFile[$item->getId()] = $this->container->getParameter('kernel.project_dir').'/data/client/lettreCheque/' . $item->getId() . '_lettre_cheque.' . $item->getFileUrl();
        }

        if (count($listChequeItem)<count($listPrime)) {
            $return = 0;
        } else {
            //$fmt_spellout = new \NumberFormatter('fr', \NumberFormatter::SPELLOUT);
            $array = array();
            $i = 0;
            foreach ($listPrime as $item) {
                // Load template
                $TBS->LoadTemplate($arraySourceFile[(int)$item['modeleId']]);

                // Format montant
                $numericMontant = str_replace('.00', '', $item['primeMontantAide']);
                $nuts = new Nuts($numericMontant, "EUR");
                $letterMontant = mb_strtoupper($nuts->convert('fr-FR'));
                $letterMontant = str_replace(',', ' ET', $letterMontant);
                $letterMontant = str_replace('-', ' ', $letterMontant);
                $montantAideLettre = $this->shortenString($letterMontant, 5);

                // Replace variables
                array_push($array, array(
                    'denomination'                  => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeDenomination']),
                    'identifiant'                   => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeIdentifiant']),
                    'adresseFacturation'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeAdresseFacturation']),
                    'complementAdresseFacturation'  => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeComplementFacturation']),
                    'codePostalFacturation'         => $item['primeCodePostalFacturation'],
                    'villeFacturation'              => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeVilleFacturation']),
                    'numeroAction'                  => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeNumeroAction']),
                    'apporteurAffaire'              => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeApporteurAffaire']),
                    'montantAide'                   => $item['primeMontantAide'],
                    'numero'                        => $listChequeItem[$i]['chequeNumero'],
                    'adresseChantier'               => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeAdresseChantier']),
                    'complementAdresseChantier'     => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeComplementChantier']),
                    'codePostalChantier'            => $item['primeCodePostalChantier'],
                    'villeChantier'                 => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeVilleChantier']),
                    'lotDateStatut8'                => $item['lotDateStatut8'],
                    'lotNumero'                     => $item['lotNumero'],
                    'onglet'                        => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['primeOnglet']),
                    'index'                         => $item['primeIndex'],
                    'clientTitreDispositif'         => iconv("UTF-8", "Windows-1252//TRANSLIT", $item['clientTitreDispositif']),
                    //'montantAideLettre'             => strtoupper($fmt_spellout->format($item['primeMontantAide']))
                    'montantAideLettre1'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $montantAideLettre[0]),
                    'montantAideLettre2'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $montantAideLettre[1])
                ));
                $TBS->MergeBlock('prime', $array);

                /* /////////////////////////////////////////////////////////////////
                                    UPDATE PRIME NUMERO
                ///////////////////////////////////////////////////////////////// */
                $primeObject = $repo_prime->find($item['primeId']);
                $primeObject->setNumero($listChequeItem[$i]['chequeNumero']);
                $EM->persist($primeObject);

                /* /////////////////////////////////////////////////////////////////
                                    UPDATE CHEQUE STATUT
                ///////////////////////////////////////////////////////////////// */
                $chequeItemObject = $repo_chequeItem->find($listChequeItem[$i]['chequeId']);
                $chequeItemObject->setStatut(1);
                $EM->persist($chequeItemObject);

                $i++;
            }

            // Declare PATH
            $source = $this->container->getParameter('kernel.project_dir') . '/data/BAT/BAT_' . $clientId . '_' . $lot->getNumero() . '_' . date("YmdHis") . '.docx';
            $destination = $this->container->getParameter('kernel.project_dir') . '/data/BAT/BAT_' . $clientId . '_' . $lot->getNumero() . '_' . date("YmdHis") . '.pdf';

            // Generate DOCX by TBS
            //$TBS->Show(OPENTBS_DOWNLOAD, 'BAT_' . $clientId . '_' . date("YmdHis") . '.docx');
            $TBS->Show(OPENTBS_FILE, $source);

            // Generate PDF by Unoconv
            /*
            $unoconv = Unoconv::create(array('unoconv.binaries' => '/usr/bin/unoconv'));
            putenv('HOME=/tmp/');
            $unoconv->transcode(
                $source,
                'pdf',
                $destination,
                '2-' . count($listPrime)
            );
            */

            // Generate PDF by Unoconv command line
            putenv('HOME=/tmp/');
            exec('unoconv -f pdf -e PageRange=2-' . (count($listPrime)+1) . ' -o ' . $destination . ' ' . $source);

            // Debug Unoconv
            /*
            putenv('HOME=/var/www/html/');
            $command = 'echo $HOME &  unoconv -vvvv --format %s --output %s %s 2>output.txt';
            $command = sprintf($command, 'pdf', $destination, $source);
            exec($command, $output, $result_var);
            */
            //dump(exec('pwd'));
            //dump(exec('command -v unoconv'));

            $return = 1;
        }

        return $return;
    }

    /* *****************************************************************
    ********************************************************************
                            F U N C T I O N S
    ********************************************************************
    *******************************************************************/
    /**
     * @param $string
     * @param $wordsReturned
     * @return mixed|string
     */
    private function shortenString($string, $wordsReturned)
    {
        $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
        $arrayString = explode(" ", $string);

        if (count($arrayString)<=$wordsReturned) {
            $return1 = $string;
            $return2 = "";
        } else {
            $return1 = implode(" ", array_slice($arrayString, 0, $wordsReturned));
            $return2 = implode(" ", array_splice($arrayString, $wordsReturned));
        }

        return array($return1, $return2);
    }
}
