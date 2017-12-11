<?php

namespace blackLabel\ImportBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Shared_Date;

use blackLabel\ImportBundle\Entity\Import_prime;
use blackLabel\ImportBundle\Entity\Import_canal;

/**
 * Class ImportService
 * @package blackLabel\ImportBundle\Service
 */
class ImportService
{
    /**
     * @var string
     */
    private $doctrine;

    /**
     *
     * @var Container
     */
    private $container;



    /**
     * ImportService constructor.
     * @param $doctrine
     * @param Container $container
     */
    public function __construct($doctrine, Container $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
    }



    /* *******************************************************************
     * *******************************************************************
                            F I N D / S E A R C H
     * *******************************************************************
     * ***************************************************************** */

    /**
     * @param $importId
     * @param $fileWebPath
     */
    public function persistXLSX($importId, $fileWebPath)
    {
        $EM = $this->doctrine->getManager();

        $sourceFile = $this->container->getParameter('kernel.project_dir')."/data/".$fileWebPath;
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject($sourceFile);

        /*
         * Autre Méthode
        $inputFileType = PHPExcel_IOFactory::identify($sourceFile);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $phpExcelObject = $objReader->load($sourceFile);
        */

        $allSheet = $phpExcelObject->getAllSheets();
        $this->updateNumeroLot($allSheet[0]->getTitle(), $importId);
        foreach ($allSheet as $sheet) {
            /* //////////////////////////////////////////////////////////
                                GET CELL DATA
            /////////////////////////////////////////////////////////// */
            $valueAllData = $this->getDataBySheet($sheet);

            // Get last 7 rows for Data Canal
            $valueCanal = array_slice($valueAllData, -7);
            array_splice($valueCanal, -1);

            // Get Data for Commande / Delete 3 first rows + 7 last rows
            $valueCommande = array_splice($valueAllData, 3);
            array_splice($valueCommande, -7);

            /* //////////////////////////////////////////////////////////
                                SET CANAL DATA
            /////////////////////////////////////////////////////////// */
            $formatValueCanal = array();
            $formatValueCanal[] = (int)$valueCanal[0]["U"];
            $formatValueCanal[] = floatval($valueCanal[1]["U"]);
            $formatValueCanal[] = floatval($valueCanal[2]["U"]);
            $formatValueCanal[] = floatval($valueCanal[3]["U"]);
            $formatValueCanal[] = floatval($valueCanal[4]["U"]);
            $formatValueCanal[] = floatval($valueCanal[5]["U"]);

            $objectCanal = new Import_canal();
            $objectCanal->setTitle($sheet->getTitle());
            $objectCanal->setLotId($importId);
            $objectCanal->setNombreCommande($formatValueCanal[0]);
            $objectCanal->setSomme($formatValueCanal[1]);
            $objectCanal->setMoyenne($formatValueCanal[2]);
            $objectCanal->setMin($formatValueCanal[3]);
            $objectCanal->setMax($formatValueCanal[4]);
            $objectCanal->setEcartType($formatValueCanal[5]);

            $EM->persist($objectCanal);
            $EM->flush();

            /* //////////////////////////////////////////////////////////
                                SET COMMANDE DATA
            /////////////////////////////////////////////////////////// */
            foreach ($valueCommande as $row) {
                // FORMAT DATA
                if ($row["B"]) $row["B"] = new \DateTime(date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($row["B"])));
                if ($row["C"]) $row["C"] = (int)$row["C"];
                if ($row["D"]) $row["D"] = strtoupper($row["D"]);
                if ($row["E"]) $row["E"] = ucfirst(strtolower($row["E"]));
                if ($row["F"]) $row["F"] = str_replace('.', '', $row["F"]);
                if ($row["G"]) $row["G"] = strtoupper($row["G"]);
                if ($row["H"]) $row["H"] = strtoupper($row["H"]);
                if ($row["L"]) $row["L"] = strtoupper($row["L"]);
                if ($row["M"]) $row["M"] = strtoupper($row["M"]);
                if ($row["R"]) $row["R"] = filter_var($row["R"],FILTER_SANITIZE_NUMBER_INT);
                if ($row["S"]) $row["S"] = filter_var($row["S"],FILTER_SANITIZE_EMAIL);
                if ($row["U"]) $row["U"] = floatval($row["U"]);

                // IMPORT RULES
                /*
                $bypass = true;
                if (('LC' != $row["A"]) || ('VIR' != $row["A"])) $bypass = false;
                if ($row["B"])
                if ($row["C"])
                */

                $objectCommande = new Import_prime();
                $objectCommande->setCanalId($objectCanal->getId());
                $objectCommande->setType($row["A"]);
                $objectCommande->setDate($row["B"]);
                $objectCommande->setNumero($row["C"]);
                $objectCommande->setNom($row["D"]);
                $objectCommande->setPrenom($row["E"]);
                $objectCommande->setSiren($row["F"]);
                $objectCommande->setDenomination($row["G"]);
                $objectCommande->setRepresentant($row["H"]);
                $objectCommande->setAdresseFacturation($row["I"]);
                $objectCommande->setComplementAdresseFacturation($row["J"]);
                $objectCommande->setCodePostalFacturation($row["K"]);
                $objectCommande->setVilleFacturation($row["L"]);
                $objectCommande->setPays($row["M"]);
                $objectCommande->setAdresseChantier($row["N"]);
                $objectCommande->setComplementAdresseChantier($row["O"]);
                $objectCommande->setCodePostalChantier($row["P"]);
                $objectCommande->setVilleChantier($row["Q"]);
                $objectCommande->setTelephone($row["R"]);
                $objectCommande->setEmail($row["S"]);
                $objectCommande->setIban($row["T"]);
                $objectCommande->setMontantAide($row["U"]);
                $objectCommande->setNumeroAction($row["V"]);
                $objectCommande->setApporteurAffaire($row["W"]);
                $objectCommande->setOnglet($row["X"]);

                $EM->persist($objectCommande);

                //dump(gettype($row['Y']));
            }//die;
            $EM->flush();

            /*
             * Autre Méthode mais ne calcule pas les formules
            $highestRow = $sheet->getHighestRow();
            $highestDataColumn = $sheet->getHighestColumn();

            for ($row=3; $row<=($highestRow-7); $row++) {
                //$rowData = $sheet->toArray();
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestDataColumn . $row, true, true, true);
                dump($rowData[0]);

                $formatData = array();
                $colNumber = PHPExcel_Cell::columnIndexFromString($highestDataColumn);
                for ($i=0; $i<=($colNumber-1); $i++) {
                    if (!is_bool($rowData[0][$i])) {
                        if (1 == $i) {
                            $rowData[0][$i] = new \DateTime($rowData[0][$i]);
                            //$date = date_format($rowData[0][$i], 'Y-m-d');
                        }

                        if (2 == $i) $rowData[0][$i] = (int)$rowData[0][$i];
                        if (5 == $i) $rowData[0][$i] = str_replace('.', '', $rowData[0][$i]);
                        if (17 == $i) $rowData[0][$i] = filter_var($rowData[0][$i],FILTER_SANITIZE_NUMBER_INT);
                        if (18 == $i) $rowData[0][$i] = filter_var($rowData[0][$i],FILTER_SANITIZE_EMAIL);
                        if (20 == $i) $rowData[0][$i] = floatval($rowData[0][$i]);
                    } else {
                        $rowData[0][$i] = null;
                    }
                    $formatData[$i] = $rowData[0][$i];
                }
                dump($formatData);

                $commandeObject = new Import_commande();
                $commandeObject->setCanalId($objectCanal->getId());
                $commandeObject->setType($formatData[0]);
                $commandeObject->setDate($formatData[1]);
                $commandeObject->setNumero($formatData[2]);
                $commandeObject->setNom($formatData[3]);
                $commandeObject->setPrenom($formatData[4]);
                $commandeObject->setSiren($formatData[5]);
                $commandeObject->setDenomination($formatData[6]);
                $commandeObject->setRepresentant($formatData[7]);
                $commandeObject->setAdresseFacturation($formatData[8]);
                $commandeObject->setComplementAdresseFacturation($formatData[9]);
                $commandeObject->setCodePostalFacturation($formatData[10]);
                $commandeObject->setVilleFacturation($formatData[11]);
                $commandeObject->setPays($formatData[12]);
                $commandeObject->setAdresseChantier($formatData[13]);
                $commandeObject->setComplementAdresseChantier($formatData[14]);
                $commandeObject->setCodePostalChantier($formatData[15]);
                $commandeObject->setVilleChantier($formatData[16]);
                $commandeObject->setTelephone($formatData[17]);
                $commandeObject->setEmail($formatData[18]);
                $commandeObject->setIban($formatData[19]);
                $commandeObject->setMontantAide($formatData[20]);
                $commandeObject->setNumeroAction($formatData[21]);
                $commandeObject->setApporteurAffaire($formatData[22]);
                $commandeObject->setOnglet($formatData[23]);

                $EM->persist($commandeObject);
            }
            $EM->flush();
            */
        }
    }


    /* *******************************************************************
     * *******************************************************************
                            F U N C T I O N S
     * *******************************************************************
     * ***************************************************************** */

    /**
     * @param $sheet
     * @return array
     */
    private function getDataBySheet($sheet)
    {
        $array = array();

        foreach ($sheet->getRowIterator() as $row) {
            $array[] = array();

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                if ($cell->getOldCalculatedValue() === null) {
                    $array[$cell->getRow()][$cell->getColumn()] = $cell->getValue();
                } else {
                    $array[$cell->getRow()][$cell->getColumn()] = $cell->getOldCalculatedValue();
                }
            }
        }

        return $array;
    }

    /**
     * @param $title
     * @param $importId
     */
    private function updateNumeroLot($title, $importId)
    {
        $EM = $this->doctrine->getManager();
        $numeroLot = filter_var($title, FILTER_SANITIZE_NUMBER_INT);

        $query = "
            UPDATE import_lot
            SET numero = " . $numeroLot . "
            WHERE id = " . $importId
        ;

        $stmt = $EM
            ->getConnection()
            ->prepare($query);
        $stmt->execute();
    }
}
