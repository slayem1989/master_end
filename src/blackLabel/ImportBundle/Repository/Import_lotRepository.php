<?php

namespace blackLabel\ImportBundle\Repository;

use whiteLabel\BackOfficeBundle\Entity\Statut_lot;

/**
 * Import_lotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Import_lotRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Import_lotRepository constructor.
     * @param $em
     * @param \Doctrine\ORM\Mapping\ClassMetadata $class
     */
    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }



    /**
     * @param $clientId
     * @return array
     */
    public function findByClient($clientId)
    {
        $query = "
            SELECT  il.id AS lotId,
                    il.numero AS lotNumero,
                    il.file_alt AS lotFilename,
                    il.date_creation AS lotDateIntegration,
                    il.statut_id AS lotStatutId,
                    il.date_statut_2 AS lotDateStatut2,
                    il.date_statut_3 AS lotDateStatut3,
                    sl.slug AS lotStatut,
                    cb.nom AS banqueNom,
                    COUNT(CASE WHEN ip.type = 'LC' THEN ip.type ELSE NULL END) AS countLC,
                    SUM(CASE WHEN ip.type = 'LC' THEN ip.montant_aide ELSE NULL END) AS montantLC,
                    COUNT(CASE WHEN ip.type = 'VIR' THEN ip.type ELSE NULL END) AS countAutre,
                    SUM(CASE WHEN ip.type = 'VIR' THEN ip.montant_aide ELSE NULL END) AS montantAutre,
                    SUM(ip.montant_aide) AS montantTotal
            FROM import_lot il
                INNER JOIN client_ c ON c.id = il.client_id
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
                INNER JOIN client_banque cb ON cb.id = il.banque_id
                INNER JOIN statut_lot sl ON sl.id = il.statut_id
            WHERE il.statut_id <> " . Statut_lot::STATUT_11 . "
                AND il.client_id = " . $clientId . "
            GROUP BY il.id
            ORDER BY il.id DESC
        ";

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param $clientId
     * @param $lotId
     * @return mixed
     */
    public function findByIdCustom($clientId, $lotId)
    {
        $query = "
            SELECT  il.id AS lotId,
                    il.numero AS lotNumero,
                    il.date_statut_1 AS lotDateStatut1,
                    il.date_statut_2 AS lotDateStatut2,
                    il.date_statut_3 AS lotDateStatut3,
                    il.date_statut_4 AS lotDateStatut4,
                    il.date_statut_5 AS lotDateStatut5,
                    il.date_statut_6 AS lotDateStatut6,
                    il.date_statut_7 AS lotDateStatut7,
                    il.date_statut_8 AS lotDateStatut8,
                    il.statut_id AS lotStatutId,
                    sl1.slug AS lotStatutSlug,
                    sl2.slug AS lotStatutSlugNext,
                    sl3.slug AS lotStatutSlugDeny4,
                    sl4.slug AS lotStatutSlugDeny5
            FROM import_lot il
                INNER JOIN statut_lot sl1 ON sl1.id = il.statut_id
                LEFT JOIN statut_lot sl2 ON sl2.id = il.statut_id + 1
                LEFT JOIN statut_lot sl3 ON sl3.id = il.statut_id - 2
                LEFT JOIN statut_lot sl4 ON sl4.id = il.statut_id - 2
            WHERE il.id = " . $lotId . "
                AND il.client_id = " . $clientId
        ;

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @param $clientId
     * @param $primeId
     * @return mixed
     */
    public function findByPrime($clientId, $primeId)
    {
        $query = "
            SELECT  il.id AS lotId,
                    il.numero AS lotNumero,
                    ip.canal_id AS canalId,
                    ip.id AS primeId
            FROM import_lot il
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
            WHERE ip.id = " . $primeId . "
                AND il.client_id = " . $clientId
        ;

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @param $clientId
     * @param $lotId
     * @return array
     */
    public function findDataNDByLot($clientId, $lotId)
    {
        $query = "
            SELECT  il.id AS lotId,
                    il.numero AS lotNumero,
                    il.file_alt AS lotFilename,
                    il.date_creation AS lotDateIntegration,
                    il.date_statut_2 AS lotDateStatut2,
                    cb.titulaire AS banqueTitulaire,
                    cb.nom AS banqueNom,
            	    cb.rib AS banqueRib,
                    cb.iban AS banqueIban,
                    cb.bic AS banqueBic,
                    ci.nom AS clientNom,
                    cand.destinataire AS clientDestinataire,
                    cand.adresse AS clientAdresse,
                    cand.complement1 AS clientComplement,
                    cand.code_postal AS clientCodePostal,
                    cand.ville AS clientVille,
                    count(ip.id) AS nombreCommande,
                    sum(ip.montant_aide) AS totalMontant,
                    ip.onglet AS canalTitle,
                    c.id AS clientId
            FROM import_lot il
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
                INNER JOIN client_ c ON c.id = il.client_id
                INNER JOIN client_information ci ON ci.id = c.client_information_id
                INNER JOIN client_adresse_note_debit cand ON cand.id = c.client_adresse_note_debit_id
                INNER JOIN client_banque cb ON cb.id = il.banque_id
            WHERE il.client_id = " . $clientId . " 
                AND il.id = " . $lotId . "
            GROUP BY ip.onglet
            ORDER BY il.id ASC
        ";

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
