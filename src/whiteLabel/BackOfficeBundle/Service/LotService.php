<?php

namespace whiteLabel\BackOfficeBundle\Service;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use blackLabel\GenericBundle\Entity\Nuts;
use Spipu\Html2Pdf\Html2Pdf;

use Unoconv\Unoconv as Unoconv;
use iio\libmergepdf\Merger;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;

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
     * @param $lotNumero
     * @return string|Response
     * @throws \iio\libmergepdf\Exception
     */
    public function generateBAT($clientId, $lotId, $lotNumero)
    {
        set_time_limit(0);
        $EM = $this->doctrine->getManager();

        /* /////////////////////////////////////////////////////////////////
                                    GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo_prime = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $listPrime = $repo_prime->findDataBATByLot($clientId, $lotId);

        /* /////////////////////////////////////////////////////////////////
                                    GET CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $repo_chequeItem = $EM->getRepository('whiteLabelBackOfficeBundle:Cheque_item');
        $listChequeItem = $repo_chequeItem->findForBATByClient($clientId, $lotId);

        /* /////////////////////////////////////////////////////////////////
                                GET MODELE LETTRE
        ///////////////////////////////////////////////////////////////// */
        $repo_modeleLettre = $EM->getRepository('whiteLabelBackOfficeBundle:ModeleLettre');
        $listModeleLettre = $repo_modeleLettre->findBy(array(
            'clientId' => $clientId
        ));
        $arrayModeleLettre = array();
        foreach ($listModeleLettre as $item) {
            $arrayModeleLettre[$item->getId()] = $this->container->getParameter('kernel.project_dir').'/data/'.$clientId.'/modeleLettre/' . $item->getId() . '_modele_lettre.' . $item->getFileUrl();
        }

        /* /////////////////////////////////////////////////////////////////
                                FORMAT LIST PRIME
        ///////////////////////////////////////////////////////////////// */
        $listPrimeBySheet = array();
        foreach ($listPrime as $item) {
            $listPrimeBySheet[$item['primeOnglet']][] = $item;
        }

        $return = false;
        if (count($listChequeItem)>count($listPrime)) {
            $i = 0;
            $j = 0;

            $folder = '/BAT';
            $pathFolder = $this->container->getParameter('kernel.project_dir').'/data/'.$clientId.$folder;
            if (!file_exists($pathFolder) || !is_dir($pathFolder)) {
                mkdir($pathFolder, 0755);
            }

            // Load TBS service
            $TBS = $this->container->get('opentbs');

            foreach ($listPrimeBySheet as $key => $value) {
                $arrayData = array();
                $countArray = count($value) + 1;
                $now = date("YmdHis");
                $iFormat = str_pad($i, 3, 000, STR_PAD_LEFT);

                foreach ($value as $item) {
                    // Load template
                    $TBS->LoadTemplate($arrayModeleLettre[$item['primeModeleId']]);

                    // Format variable montant
                    $numericMontant = str_replace('.00', '', $item['primeMontantAide']);
                    $nuts = new Nuts($numericMontant, "EUR");
                    $letterMontant = mb_strtoupper($nuts->convert('fr-FR'));
                    $letterMontant = str_replace(',', ' ET', $letterMontant);
                    $letterMontant = str_replace('-', ' ', $letterMontant);
                    $montantAideLettre = $this->shortenString($letterMontant, 5);

                    // Replace variables
                    array_push($arrayData, array(
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
                        'montantAideLettre1'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $montantAideLettre[0]),
                        'montantAideLettre2'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $montantAideLettre[1])
                    ));
                    $TBS->MergeBlock('prime', $arrayData);

                    /* /////////////////////////////////////////////////////////////////
                                        UPDATE PRIME NUMERO
                    ///////////////////////////////////////////////////////////////// */
                    $primeObject = $repo_prime->find($item['primeId']);
                    $primeObject->setNumero($listChequeItem[$j]['chequeNumero']);
                    $EM->persist($primeObject);

                    /* /////////////////////////////////////////////////////////////////
                                        UPDATE CHEQUE STATUT
                    ///////////////////////////////////////////////////////////////// */
                    $chequeItemObject = $repo_chequeItem->find($listChequeItem[$j]['chequeId']);
                    $chequeItemObject->setStatut(1);
                    $EM->persist($chequeItemObject);

                    $j++;
                }

                // Declare PATH
                $source = $this->container->getParameter('kernel.project_dir')
                    . '/data/'.$clientId.'/BAT/BAT_'
                    . $clientId . '_'
                    . $lotNumero . '_'
                    . '(' . $iFormat . ')_'
                    . $key . '_'
                    . $now
                    . '.docx';

                $destination = $this->container->getParameter('kernel.project_dir')
                    . '/data/'.$clientId.'/BAT/BAT_'
                    . $clientId . '_'
                    . $lotNumero . '_'
                    . '(' . $iFormat . ')_'
                    . $key . '_'
                    . $now
                    . '.pdf';

                // Generate DOCX by TBS
                $TBS->Show(OPENTBS_FILE, $source);

                // Generate PDF by Unoconv
                $unoconv = Unoconv::create(array('unoconv.binaries' => '/usr/bin/unoconv'));
                putenv('HOME=/tmp/');
                $unoconv->transcode(
                    $source,
                    'pdf',
                    $destination,
                    '2-' . $countArray
                );

                $i++;
            }

            /*
            // Declare PATH
            $source = $this->container->getParameter('kernel.project_dir') . '/data/BAT/BAT_' . $clientId . '_' . $lot->getNumero() . '_' . date("YmdHis") . '.docx';
            $destination = $this->container->getParameter('kernel.project_dir') . '/data/BAT/BAT_' . $clientId . '_' . $lot->getNumero() . '_' . date("YmdHis") . '.pdf';

            // Generate DOCX by TBS
            //$TBS->Show(OPENTBS_DOWNLOAD, 'BAT_' . $clientId . '_' . date("YmdHis") . '.docx');
            $TBS->Show(OPENTBS_FILE, $source);

            // Generate PDF by Unoconv
            //$unoconv = Unoconv::create(array('unoconv.binaries' => '/usr/bin/unoconv'));
            //putenv('HOME=/tmp/');
            //$unoconv->transcode(
            //    $source,
            //    'pdf',
            //    $destination,
            //    '2-' . $countListPrime
            //);

            // Generate PDF by Unoconv command line
            putenv('HOME=/tmp/');
            exec('unoconv -f pdf -e PageRange=2-' . ($countListPrime + 1) . ' -o ' . $destination . ' ' . $source);

            // Debug Unoconv
            //putenv('HOME=/var/www/html/');
            //$command = 'echo $HOME &  unoconv -vvvv --format %s --output %s %s 2>output.txt';
            //$command = sprintf($command, 'pdf', $destination, $source);
            //exec($command, $output, $result_var);
            //dump(exec('pwd'));
            //dump(exec('command -v unoconv'));
            */

            $EM->flush();

            // Create file to download
            $finder = new Finder;
            $finder->files()->in($this->container->getParameter('kernel.project_dir') . '/data/'.$clientId.'/BAT/')->name('*.pdf')->sortByName();

            $merger = new Merger;
            $merger->addFinder($finder);

            $createdPdf = $merger->merge();

            $destinationFinal = $this->container->getParameter('kernel.project_dir')
                . '/data/'.$clientId.'/BAT/BAT_'
                . $clientId . '_'
                . $lotNumero
                . '.pdf';
            file_put_contents($destinationFinal, $createdPdf);

            $return = true;
        }

        return $return;
    }

    /**
     * @param $clientId
     * @param $primeId
     * @param $lotNumero
     * @return Response
     */
    public function generatePrimeBAT($clientId, $primeId, $lotNumero)
    {
        $EM = $this->doctrine->getManager();

        /* /////////////////////////////////////////////////////////////////
                                    GET PRIME
        ///////////////////////////////////////////////////////////////// */
        $repo_prime = $EM->getRepository('blackLabelImportBundle:Import_prime');
        $prime = $repo_prime->findDataBATByPrime($clientId, $primeId);

        /* /////////////////////////////////////////////////////////////////
                                GET MODELE LETTRE
        ///////////////////////////////////////////////////////////////// */
        $repo_modeleLettre = $EM->getRepository('whiteLabelBackOfficeBundle:ModeleLettre');
        $listModeleLettre = $repo_modeleLettre->findBy(array(
            'clientId' => $clientId
        ));
        $arrayModeleLettre = array();
        foreach ($listModeleLettre as $item) {
            $arrayModeleLettre[$item->getId()] = $this->container->getParameter('kernel.project_dir').'/data/'.$clientId.'/modeleLettre/' . $item->getId() . '_modele_lettre.' . $item->getFileUrl();
        }



        // Load TBS service
        $TBS = $this->container->get('opentbs');

        // Load template
        $TBS->LoadTemplate($arrayModeleLettre[$prime['primeModeleId']]);

        // Format variable montant
        $numericMontant = str_replace('.00', '', $prime['primeMontantAide']);
        $nuts = new Nuts($numericMontant, "EUR");
        $letterMontant = mb_strtoupper($nuts->convert('fr-FR'));
        $letterMontant = str_replace(',', ' ET', $letterMontant);
        $letterMontant = str_replace('-', ' ', $letterMontant);
        $montantAideLettre = $this->shortenString($letterMontant, 5);

        // Replace variables
        $arrayData = array();
        array_push($arrayData, array(
            'denomination'                  => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeDenomination']),
            'identifiant'                   => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeIdentifiant']),
            'adresseFacturation'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeAdresseFacturation']),
            'complementAdresseFacturation'  => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeComplementFacturation']),
            'codePostalFacturation'         => $prime['primeCodePostalFacturation'],
            'villeFacturation'              => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeVilleFacturation']),
            'numeroAction'                  => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeNumeroAction']),
            'apporteurAffaire'              => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeApporteurAffaire']),
            'montantAide'                   => $prime['primeMontantAide'],
            'numero'                        => $prime['primeNumero'],
            'adresseChantier'               => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeAdresseChantier']),
            'complementAdresseChantier'     => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeComplementChantier']),
            'codePostalChantier'            => $prime['primeCodePostalChantier'],
            'villeChantier'                 => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeVilleChantier']),
            'lotDateStatut8'                => $prime['lotDateStatut8'],
            'lotNumero'                     => $prime['lotNumero'],
            'onglet'                        => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['primeOnglet']),
            'index'                         => $prime['primeIndex'],
            'clientTitreDispositif'         => iconv("UTF-8", "Windows-1252//TRANSLIT", $prime['clientTitreDispositif']),
            'montantAideLettre1'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $montantAideLettre[0]),
            'montantAideLettre2'            => iconv("UTF-8", "Windows-1252//TRANSLIT", $montantAideLettre[1])
        ));
        $TBS->MergeBlock('prime', $arrayData);

        // Declare PATH
        $source = $this->container->getParameter('kernel.project_dir')
            . '/data/'.$clientId.'/BAT/BAT_'
            . $clientId . '_'
            . $lotNumero . '_'
            . 'P' . $prime['primeNumero'] . '_'
            . date("YmdHis")
            . '.docx';

        $destination = $this->container->getParameter('kernel.project_dir')
            . '/data/'.$clientId.'/BAT/BAT_'
            . $clientId . '_'
            . $lotNumero . '_'
            . 'P' . $prime['primeNumero'] . '_'
            . date("YmdHis")
            . '.pdf';

        // Generate DOCX by TBS
        $TBS->Show(OPENTBS_FILE, $source);

        // Generate PDF by Unoconv
        $unoconv = Unoconv::create(array('unoconv.binaries' => '/usr/bin/unoconv'));
        putenv('HOME=/tmp/');
        $unoconv->transcode(
            $source,
            'pdf',
            $destination,
            '2-2'
        );

        // Download file
        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent(file_get_contents($destination));
        $response->headers->set('Content-Type', 'application/pdf; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . basename($destination));

        if (file_exists($destination)) {
            unlink($source);
            unlink($destination);
        }

        return $response;
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
