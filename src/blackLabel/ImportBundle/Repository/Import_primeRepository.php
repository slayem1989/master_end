<?php

namespace blackLabel\ImportBundle\Repository;

/**
 * Import_primeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Import_primeRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $clientId
     * @return array
     */
    public function findByClient($clientId)
    {
        $EM = $this->getEntityManager('EM_MYSQL');

        $query = "
            SELECT  il.id AS lotId,
                    il.numero AS lotNumero,
                    ip.canal_id AS canalId,
                    ip.date_creation AS primeDateIntegration,
                    ip.id AS primeId,
                    ip.numero AS primeNumero,
                    ip.type AS primeType,
                    ip.nom AS primeNom,
                    ip.prenom AS primePrenom,
                    ip.siren AS primeSiren,
                    ip.denomination AS primeDenomination,
                    ip.representant AS primeRepresentant,
                    ip.code_postal_facturation AS primeCodePostal,
                    ip.ville_facturation AS primeVille,
                    ip.telephone AS primeTelephone,
                    ip.email AS primeEmail,
                    ip.onglet AS primeOnglet,
                    s.slug AS lotStatut
            FROM import_lot il
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
                INNER JOIN statut s ON s.id = il.statut_id
            WHERE il.client_id = " . $clientId . "
                AND ic.title LIKE '%ecap%'
            ORDER BY ip.id DESC
        ";

        $stmt = $EM
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}