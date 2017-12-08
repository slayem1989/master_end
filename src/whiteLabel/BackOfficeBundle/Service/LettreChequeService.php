<?php

namespace whiteLabel\BackOfficeBundle\Service;

use Spipu\Html2Pdf\Html2Pdf;

/**
 * Class LettreChequeService
 * @package whiteLabel\BackOfficeBundle\Service
 */
class LettreChequeService
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
     * LettreChequeService constructor.
     * @param $doctrine
     * @param $container
     */
    public function __construct(
        $doctrine,
        $container
    ) {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }



    /* *****************************************************************
    ********************************************************************
                            F I N D / S E A R C H
    ********************************************************************
    *******************************************************************/
    /**
     * @param $entityId
     * @param $fileWebPath
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     */
    public function persistHTML($entityId, $fileWebPath)
    {
        $sourceFile = $this->container->getParameter('kernel.project_dir')."/data/".$fileWebPath;
        $contentFile = file_get_contents($sourceFile);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->WriteHTML($contentFile);
        $html2pdf->Output($this->container->getParameter('kernel.project_dir').'/data/client/lettreCheque/'.$entityId.'_lettre_cheque.pdf', 'F');
    }

    /* *****************************************************************
    ********************************************************************
                            F U N C T I O N S
    ********************************************************************
    *******************************************************************/
}
