<?php

namespace blackLabel\ImportBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Shared_Date;

use whiteLabel\BackOfficeBundle\Entity\Statut_lot;
use blackLabel\ImportBundle\Entity\Import_canal;
use blackLabel\ImportBundle\Entity\Import_prime;

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
     * @var string
     */
    private $router;

    /**
     * @var string
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     *
     * @var Container
     */
    private $container;



    /**
     * ImportService constructor.
     * @param $doctrine
     * @param $router
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $environment
     * @param Container $container
     */
    public function __construct(
        $doctrine,
        $router,
        \Swift_Mailer $mailer,
        \Twig_Environment $environment,
        Container $container
    ) {
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->environment = $environment;
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
     * @param $dateImport
     * @param $auteurImport
     * @return bool
     */
    public function persistXLSX($importId, $fileWebPath, $dateImport, $auteurImport)
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

        // Update Numero du Lot
        $numeroLot = filter_var($allSheet[0]->getTitle(), FILTER_SANITIZE_NUMBER_INT);
        $this->updateNumeroLot($importId, $numeroLot);

        $isValid = true;
        foreach ($allSheet as $sheet) {
            /* //////////////////////////////////////////////////////////
                                PARSE DATA FILE BY SHEET
            /////////////////////////////////////////////////////////// */
            $valueAllData = $this->getDataBySheet($sheet);

            // Get Data for Canal / Delete last 7 rows
            $valueCanal = array_slice($valueAllData, -7);
            array_splice($valueCanal, -1);

            // Get Data for Prime / Delete 3 first rows + 7 last rows
            $valuePrime = array_splice($valueAllData, 3);
            array_splice($valuePrime, -7);

            /* //////////////////////////////////////////////////////////
                                SET CANAL DATA
            /////////////////////////////////////////////////////////// */
            $formatValueCanal = array();

            // TOTAL ROW
            $formatValueCanal[] = (int)$valueCanal[0]['U'];

            // SOMME
            $formatValueCanal[] = floatval($valueCanal[1]['U']);

            // MOYENNE
            $formatValueCanal[] = floatval($valueCanal[2]['U']);

            // MIN
            $formatValueCanal[] = floatval($valueCanal[3]['U']);

            // MAXI
            $formatValueCanal[] = floatval($valueCanal[4]['U']);

            // ECARTYPE
            $formatValueCanal[] = floatval($valueCanal[5]['U']);

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
            $EM->clear();

            /* //////////////////////////////////////////////////////////
                                SET PRIME DATA
            /////////////////////////////////////////////////////////// */
            $i = 3;
            $arraydoublon = array();
            foreach ($valuePrime as $row) {
                // TYPE
                if ($row['A'] && ('' != $row['A'] || null != $row['A']) && (('LC' == $row['A']) || ('VIR' == $row['A']))) {
                    $row['A'] = strtoupper($row['A']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne A incorrecte');
                    $row['A'] = '';
                }

                // DATE
                if (($row['B'] && ('' != $row['B'] || null != $row['B'])) && true == $this->isValidDate($row['B'])) {
                    //$row['B'] = new \DateTime(date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($row['B'])));
                    //$row['B'] = PHPExcel_Shared_Date::ExcelToPHPObject($row['B']);
                    $row['B'] = new \DateTime(\PHPExcel_Style_NumberFormat::toFormattedString($row['B'], 'Y-m-d'));
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne B incorrecte');
                    $row['B'] = new \DateTime($this->container->getParameter('date_reference'));
                }

                // NUMERO
                if ($row['C'] && ('' != $row['C'] || null != $row['C']) && true == is_int((int)$row['C']) && !array_key_exists((int)$row['C'], $arraydoublon)) {
                    $row['C'] = (int)$row['C'];
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne C incorrecte');
                    $row['C'] = 9999999;
                }

                // NOM
                if ($row['D']) $row['D'] = strtoupper($row['D']);

                // PRENOM
                if ($row['E']) $row['E'] = ucfirst(strtolower($row['E']));

                // SIREN
                if ($row['F']) $row['F'] = str_replace('.', '', $row['F']);

                // DENOMINATION
                if ($row['G']) $row['G'] = strtoupper($row['G']);

                // REPRESENTANT
                if ($row['H']) $row['H'] = strtoupper($row['H']);

                // NOM PRENOM SIREN DENOMINATION REPRESENTANT
                if (
                    !$row['D'] && ('' == $row['D'] || null == $row['D']) &&
                    !$row['E'] && ('' == $row['E'] || null == $row['E']) &&
                    !$row['F'] && ('' == $row['F'] || null == $row['F']) &&
                    !$row['G'] && ('' == $row['G'] || null == $row['G']) &&
                    !$row['H'] && ('' == $row['H'] || null == $row['H'])
                ) {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne D E F G H incorrecte');
                }

                // ADRESSE FACTURATION
                if ($row['I'] && ('' != $row['I'] || null != $row['I'])) {
                    $row['I'] = trim($row['I']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne I incorrecte');
                    $row['I'] = '';
                }

                // COMPLEMENT FACTURATION
                if ($row['J']) {
                    $row['J'] = trim($row['J']);
                }

                // CODE POSTAL FACTURATION
                if ($row['K'] && ('' != $row['K'] || null != $row['K'])) {
                    $row['K'] = trim($row['K']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne K incorrecte');
                    $row['K'] = '';
                }

                // VILLE FACTURATION
                if ($row['L'] && ('' != $row['L'] || null != $row['L'])) {
                    $row['L'] = strtoupper($row['L']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne L incorrecte');
                    $row['L'] = '';
                }

                // PAYS FACTURATION
                if ($row['M']) {
                    $row['M'] = strtoupper($row['M']);
                }

                // ADRESSE CHANTIER
                if ($row['N'] && ('' != $row['N'] || null != $row['N'])) {
                    $row['N'] = trim($row['N']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne N incorrecte');
                    $row['N'] = '';
                }

                // COMPLEMENT CHANTIER
                if ($row['O']) {
                    $row['O'] = trim($row['O']);
                }

                // CODE POSTAL CHANTIER
                if ($row['P'] && ('' != $row['P'] || null != $row['P'])) {
                    $row['P'] = trim($row['P']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne P incorrecte');
                    $row['P'] = '';
                }

                // VILLE CHANTIER
                if ($row['Q'] && ('' != $row['Q'] || null != $row['Q'])) {
                    $row['Q'] = strtoupper($row['Q']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne Q incorrecte');
                    $row['Q'] = '';
                }

                // TELEPHONE
                if ($row['R']) {
                    if (true == $this->isValidTelephone($row['R'])) {
                        $row['R'] = filter_var($row['R'],FILTER_SANITIZE_NUMBER_INT);
                    } else {
                        $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne R incorrecte');
                    }
                }

                // MAIL
                if ($row['S']) {
                    if (true == filter_var($row['S'],FILTER_VALIDATE_EMAIL)) {
                        $row['S'] = filter_var($row['S'],FILTER_SANITIZE_EMAIL);
                    } else {
                        $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne S incorrecte');
                    }
                }

                // IBAN
                if ($row['T']) {
                    $row['T'] = strtoupper($row['T']);
                }

                // MONTANT AIDE
                if ($row['U'] && ('' != $row['U'] || null != $row['U']) && true == $this->isValidDecimal($row['U'])) {
                    $row['U'] = floatval($row['U']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne U incorrecte');
                    $row['U'] = floatval(0);
                }

                // NUMERO ACTION
                if ($row['V']) {
                    $row['V'] = trim($row['V']);
                }

                // APPORTEUR AFFAIRE
                if ($row['W']) {
                    $row['W'] = trim($row['W']);
                }

                // ONGLET
                if ($row['X']) {
                    $row['X'] = trim($row['X']);
                }

                // NOM MODELE
                if ($row['Y'] && ('' != $row['Y'] || null != $row['Y'])) {
                    $row['Y'] = trim($row['Y']);
                } else {
                    $this->createErrorFile($importId, 'Ligne: '.$i.' | Motif: Colonne Y incorrecte');
                    $row['Y'] = '';
                }

                $objectPrime = new Import_prime();
                $objectPrime->setCanalId($objectCanal->getId());
                $objectPrime->setIndex($row['C']);
                $objectPrime->setType($row['A']);
                $objectPrime->setDate($row['B']);
                $objectPrime->setNumero(null);

                if (($row['D'] && $row['E']) || ('' != $row['D'] && '' != $row['E'])) {
                    $objectPrime->setNom($row['D']);
                    $objectPrime->setPrenom($row['E']);
                } else {
                    $objectPrime->setSiren($row['F']);
                    $objectPrime->setDenomination($row['G']);
                    $objectPrime->setRepresentant($row['H']);
                }

                $objectPrime->setAdresseFacturation($row['I']);
                $objectPrime->setComplementFacturation($row['J']);
                $objectPrime->setCodePostalFacturation($row['K']);
                $objectPrime->setVilleFacturation($row['L']);
                $objectPrime->setPaysFacturation($row['M']);
                $objectPrime->setAdresseChantier($row['N']);
                $objectPrime->setComplementChantier($row['O']);
                $objectPrime->setCodePostalChantier($row['P']);
                $objectPrime->setVilleChantier($row['Q']);
                $objectPrime->setTelephone($row['R']);
                $objectPrime->setEmail($row['S']);
                $objectPrime->setIban($row['T']);
                $objectPrime->setMontantAide($row['U']);
                $objectPrime->setNumeroAction($row['V']);
                $objectPrime->setApporteurAffaire($row['W']);
                $objectPrime->setOnglet($row['X']);
                $objectPrime->setNomModele($row['Y']);

                $EM->persist($objectPrime);

                $i++;
                $arraydoublon[(int)$row['C']] = 'doublon';
            }

            if (file_exists($this->container->getParameter('kernel.project_dir')."/data/import/error/".$importId."_import_error.txt")) {
                $isValid = false;
                $EM->clear();

                /* /////////////////////////////////////////////////////////////////
                                            GET CANAL
                ///////////////////////////////////////////////////////////////// */
                $repo_canal = $EM->getRepository('blackLabelImportBundle:Import_canal');
                $dataCanal = $repo_canal->findOneBy(array(
                    'lotId' => $importId
                ));

                $EM->remove($dataCanal);

                $this->sendErrorFile($importId, $numeroLot, $dateImport, $auteurImport);
            }

            $EM->flush();
            $EM->clear();
            break;
        }

        return $isValid;
    }

    /**
     * @param $importId
     */
    public function updateStatutLot($importId)
    {
        $EM = $this->doctrine->getManager();
        $now = new \DateTime();

        $query = "
            UPDATE import_lot
            SET statut_id = " . Statut_lot::STATUT_11 . ", date_statut_11 = '" . $now->format('Y-m-d H:i:s') . "'
            WHERE id = " . $importId
        ;

        $stmt = $EM
            ->getConnection()
            ->prepare($query);
        $stmt->execute();
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
     * @param $importId
     * @param $numeroLot
     */
    private function updateNumeroLot($importId, $numeroLot)
    {
        $EM = $this->doctrine->getManager();

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

    /**
     * @param $importId
     * @param $content
     */
    private function createErrorFile($importId, $content)
    {
        $folder = 'error';
        $pathFolder = $this->container->getParameter('kernel.project_dir')."/data/import/".$folder;
        if (!is_dir($pathFolder)) {
            mkdir($pathFolder);
            chmod($pathFolder, 0755);
        }

        $file = fopen( $this->container->getParameter('kernel.project_dir')."/data/import/error/".$importId."_import_error.txt", "a+" );
        fwrite($file,$content.chr(13));
        fclose($file);
    }

    /**
     * @param $importId
     * @param $numeroLot
     * @param $dateImport
     * @param $auteurImport
     * @return int
     */
    private function sendErrorFile($importId, $numeroLot, $dateImport, $auteurImport)
    {
        /* /////////////////////////////////////////////////////////////////
                                    SEND EMAIL
        ///////////////////////////////////////////////////////////////// */
        $subject = 'Lot ' . $numeroLot . ' / Rapport d\'erreur';
        $templatePath = 'whiteLabelBackOfficeBundle:Lot:inc/email/error.html.twig';

        $message = (\Swift_Message::newInstance(
            $subject, $this->environment->render($templatePath, array(
            'lotNumero'     => $numeroLot,
            'dateImport'    => $dateImport,
            'auteurImport'  => $auteurImport
        )), 'text/html')
            ->setFrom($this->container->getParameter('mailer_address_from'))
            ->setTo($this->container->getParameter('import_error_recipient'))
        );

        $attachment = \Swift_Attachment::fromPath($this->container->getParameter('kernel.project_dir')."/data/import/error/".$importId."_import_error.txt");
        $message->attach($attachment);

        return $this->mailer->send($message);
    }

    /**
     * @param $value
     * @return bool
     */
    private function isValidDate($value)
    {
        $regexp = "#([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))#";

        if (!preg_match($regexp, $value)) {
            $return = false;
        } else {
            $return = true;
        }

        return $return;
    }

    /**
     * @param $value
     * @return int
     */
    private function isValidTelephone($value)
    {
        $regexp = "#^0[0-9]([-. ]?[0-9]{2}){4}$#";
        //$regexp = '#^0[0-9]{1}\d{8}$#';

        if (!preg_match($regexp, $value)) {
            $return = false;
        } else {
            $return = true;
        }

        return $return;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isValidDecimal($value)
    {
        $regexp = "#^\d*(\.?\d{0,2})+$#";

        if (!preg_match($regexp, $value)) {
            $return = false;
        } else {
            $return = true;
        }

        return $return;
    }
}
