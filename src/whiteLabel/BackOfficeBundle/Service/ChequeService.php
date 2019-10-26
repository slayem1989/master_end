<?php

namespace whiteLabel\BackOfficeBundle\Service;

use whiteLabel\BackOfficeBundle\Entity\Cheque_item;
use whiteLabel\BackOfficeBundle\Entity\Statut_prime;

use PHPExcel_IOFactory;

/**
 * Class ChequeService
 * @package whiteLabel\BackOfficeBundle\Service
 */
class ChequeService
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
     * ChequeService constructor.
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
     * @param $clientId
     * @param $stockId
     * @param $banqueId
     * @param $first
     * @param $last
     * @return array
     */
    public function createChequeItem($clientId, $stockId, $banqueId, $first, $last)
    {
        $EM = $this->doctrine->getManager();

        /* /////////////////////////////////////////////////////////////////
                                GET CHEQUE BY BANQUE
        ///////////////////////////////////////////////////////////////// */
        $repo_chequeItem = $EM->getRepository('whiteLabelBackOfficeBundle:Cheque_item');
        $listChequeItem = $repo_chequeItem->findByBanque($clientId, $banqueId);

        $arrayChequeBDD = array();
        foreach ($listChequeItem as $item) {
            $arrayChequeBDD[$item['chequeNumero']] = $item['chequeId'];
        }

        $first = (int)$first;
        $last = (int)$last;
        $delta_increment = ($last - $first) + $first;

        $arrayCheque = array();
        for ($i=$first; $i<=$delta_increment; $i++) {
            if (array_key_exists($this->formatNumeroCheque($i), $arrayChequeBDD)) {
                $arrayCheque = array();
                break;
            } else {
                $objectCheque = new Cheque_item();
                $objectCheque->setStockId($stockId);
                $objectCheque->setNumero($this->formatNumeroCheque($i));

                $arrayCheque[] = $objectCheque;
            }
        }

        return $arrayCheque;
    }

    /**
     * @param $fileWebPath
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function processRapprochementBancaire($fileWebPath)
    {
        $EM = $this->doctrine->getManager();
        $historiqueService = $this->container->get('black_label.service.historique');

        $sourceFile = $this->container->getParameter('kernel.project_dir')."/data/".$fileWebPath;

        $inputFileType = PHPExcel_IOFactory::identify($sourceFile);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $phpExcelObject = $objReader->load($sourceFile);

        $sheet=$phpExcelObject->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        //Read file line by line
        for ($row = 2; $row <= $highestRow; $row++) {
            $single_row = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, true, true, true);

            // Format Montant Debite
            $montantDebite= filter_var($single_row[0][8], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            /* /////////////////////////////////////////////////////////////////
                                    UPDATE PRIME
            ///////////////////////////////////////////////////////////////// */
            $repo = $EM->getRepository('blackLabelImportBundle:Import_prime');
            $prime = $repo->findOneBy(array(
                'numeroCheque'  => $single_row[0][4],
                'montantDebite' => null
            ));

            if ($prime) {
                $prime->setMontantDebite($montantDebite);
                $prime->setDateOperation(\DateTime::createFromFormat('m-d-y', $single_row[0][5]));
                $prime->setStatutId(Statut_prime::STATUT_5);

                $EM->persist($prime);

                /* //////////////////////////////////////////////////////////
                                    UPDATE HISTORIQUE
                /////////////////////////////////////////////////////////// */
                $historique = $historiqueService->savePrime(
                    $prime->getId(),
                    Statut_prime::STATUT_SLUG_5,
                    $fileWebPath,
                    Statut_prime::STATUT_5
                );
                $EM->persist($historique);
            }
        }

        $EM->flush();
        $EM->clear();
    }

    /* *****************************************************************
    ********************************************************************
                            F U N C T I O N S
    ********************************************************************
    *******************************************************************/
    /**
     * @param $data
     * @return string
     */
    public function formatNumeroCheque($data)
    {
        $pad_length = 7;
        $pad_char = 0000000;
        $data_format = str_pad($data, $pad_length, $pad_char, STR_PAD_LEFT);

        return $data_format;
    }
}
