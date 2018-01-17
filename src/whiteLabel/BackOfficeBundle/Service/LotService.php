<?php

namespace whiteLabel\BackOfficeBundle\Service;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use blackLabel\GenericBundle\Entity\Nuts;
use Spipu\Html2Pdf\Html2Pdf;

use Unoconv\Unoconv as Unoconv;

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
     * @param $listPrime
     */
    public function generateBAT($clientId, $listPrime)
    {
        set_time_limit(0);
        $TBS = $this->container->get('opentbs');

        /* /////////////////////////////////////////////////////////////////
                                GET LETTRE CHEQUE
        ///////////////////////////////////////////////////////////////// */
        $EM = $this->doctrine->getManager();
        $repo = $EM->getRepository('whiteLabelBackOfficeBundle:LettreCheque');
        $lettreCheque = $repo->findOneBy(array(
            'clientId' => $clientId
        ));
        $fileWebPath= '/client/lettreCheque/' . $lettreCheque->getId() . '_lettre_cheque.' . $lettreCheque->getFileUrl();
        $sourceFile = $this->container->getParameter('kernel.project_dir')."/data".$fileWebPath;

        //$fmt_spellout = new \NumberFormatter('fr', \NumberFormatter::SPELLOUT);
        $array = array();
        $i = 0;
        foreach ($listPrime as $item) {
            if ($i <3) {
                // Load template
                $TBS->LoadTemplate($sourceFile);

                $nuts = new Nuts($item['primeMontantAide'], "EUR");

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
                    'numero'                        => $item['primeNumero'],
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
                    'montantAideLettre'             => iconv("UTF-8", "Windows-1252//TRANSLIT", mb_strtoupper($nuts->convert('fr-FR')))
                ));
                $TBS->MergeBlock('prime', $array);
            }
            $i++;
        }

        // Send file
        //$TBS->Show(OPENTBS_DOWNLOAD, 'BAT_' . $clientId . '_' . date("YmdHis") . '.docx');
        $TBS->Show(OPENTBS_FILE, $this->container->getParameter('kernel.project_dir').'/data/BAT_' . $clientId . '_' . date("YmdHis") . '.docx');

        // Generate PDF
        $source = $this->container->getParameter('kernel.project_dir').'/data/BAT.docx';
        $destination = $this->container->getParameter('kernel.project_dir').'/data/document.pdf';

        $unoconv = Unoconv::create(array('unoconv.binaries' => '/usr/bin/unoconv',));
        //putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
        $unoconv->transcode(
            $source,
            'pdf',
            $destination
        );



        /*
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($sourceFile);
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'HTML');

        $xmlWriter->save($this->container->getParameter('kernel.project_dir').'/data/test.html');
        */

        /*
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($sourceFile);
        $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
        $htmlWriter->save($this->container->getParameter('kernel.project_dir').'/data/test.html');
        */

        /*
        $test = new \PhpOffice\PhpWord\TemplateProcessor($sourceFile);
        $test->saveAs($this->container->getParameter('kernel.project_dir').'/data/test.odt');



        \PhpOffice\PhpWord\Settings::setPdfRendererPath(new tcpdf());
        \PhpOffice\PhpWord\Settings::setPdfRendererName('TCPDF');

        $phpWord = \PhpOffice\PhpWord\IOFactory::load($this->container->getParameter('kernel.project_dir').'/data/test.odt');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');

        $xmlWriter->save($this->container->getParameter('kernel.project_dir').'/data/test.pdf');
        */

        /*
        $template = $this->container->get('templating')->render($this->container->getParameter('kernel.project_dir').'/data/test.odt');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($template);
        $html2pdf->Output('test.pdf', 'D');
        */
    }

    /* *****************************************************************
    ********************************************************************
                            F U N C T I O N S
    ********************************************************************
    *******************************************************************/
}
