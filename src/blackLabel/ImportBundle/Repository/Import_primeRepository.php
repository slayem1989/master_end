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
     * Import_primeRepository constructor.
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
                    ip.canal_id AS canalId,
                    ip.date AS primeDateIntegration,
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
                    sl.slug AS lotStatut
            FROM import_lot il
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
                INNER JOIN statut_lot sl ON sl.id = il.statut_id
            WHERE il.client_id = " . $clientId . "
                AND ic.title LIKE '%ecap%'
            ORDER BY ip.id DESC
        ";

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param $clientId
     * @param string $where
     * @return mixed
     */
    public function countByClient($clientId, $where='')
    {
        $query = "
            SELECT COUNT(ip.id) AS countId
            FROM import_lot il
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
                INNER JOIN statut_prime sp ON sp.id = ip.statut_id
            WHERE il.client_id = " . $clientId . "
                AND ic.title LIKE '%ecap%'" . $where . "
        ";

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @param $clientId
     * @param $orderBy
     * @param $orderType
     * @param $start
     * @param $length
     * @param string $where
     * @return array
     */
    public function findAjaxByClient($clientId, $orderBy, $orderType, $start, $length, $where='')
    {
        $query = "
            SELECT  il.numero AS lotNumero,
                    ip.id AS primeId,
                    CASE WHEN ('' != ip.numero)
                        THEN ip.numero
                        ELSE 'En attente'
                    END AS primeNumero,
                    CASE WHEN ('' != ip.prenom AND '' != ip.nom)
                        THEN CONCAT(' ', CONCAT(UCASE(LEFT(ip.prenom, 1)), LCASE(SUBSTRING(ip.prenom, 2))), ' ', UPPER(ip.nom))
                        ELSE CONCAT_WS(' ', UPPER(ip.siren), '<br>', UPPER(ip.denomination), '<br>', UPPER(ip.representant))
                    END AS primeIdentifiant,
                    CONCAT(ip.code_postal_facturation, ' ', ip.ville_facturation) AS primeVille,
                    ip.type AS primeType,
                    ip.email AS primeEmail,
                    ip.telephone AS primeTelephone,
                    DATE_FORMAT(ip.date, '%d/%m/%Y') AS primeDateIntegration,
                    ip.onglet AS primeOnglet,
                    sp.slug AS primeStatut,
                    '' AS action,
                    GROUP_CONCAT(CONCAT_WS('', cp.content, '<>', cp.auteur_creation, '<>', DATE_FORMAT(cp.date_creation, '%d/%m/%Y à %H:%i:%s')) ORDER BY cp.id DESC SEPARATOR '|') AS commentaire            
            FROM import_lot il
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
                INNER JOIN statut_prime sp ON sp.id = ip.statut_id
                LEFT JOIN commentaire_prime cp ON cp.prime_id = ip.id
            WHERE il.client_id = " . $clientId . "
                AND ic.title LIKE '%ecap%'" . $where . "
            GROUP BY ip.id
            ORDER BY " . $orderBy . " " . $orderType . "
            LIMIT " . $start . "," . $length . "
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
     * @return array
     */
    public function findDataBATByLot($clientId, $lotId)
    {
        $query = "
            SELECT  ip.id AS primeId,
                    CASE WHEN ('' != ip.prenom AND '' != ip.nom)
                        THEN ''
                        ELSE UPPER(ip.denomination)
                    END AS primeDenomination,
                    CASE WHEN ('' != ip.prenom AND '' != ip.nom)
                        THEN CONCAT(' ', CONCAT(UCASE(LEFT(ip.prenom, 1)), LCASE(SUBSTRING(ip.prenom, 2))), ' ', UPPER(ip.nom))
                        ELSE UPPER(ip.representant)
                    END AS primeIdentifiant,
                    ip.adresse_facturation AS primeAdresseFacturation,
                    ip.complement_facturation AS primeComplementFacturation,
                    ip.code_postal_facturation AS primeCodePostalFacturation,
                    ip.ville_facturation AS primeVilleFacturation,
                    ip.numero_action AS primeNumeroAction,
                    ip.apporteur_affaire AS primeApporteurAffaire,
                    ip.montant_aide AS primeMontantAide,
                    '' AS primeMontantAideLettre,
                    ip.numero AS primeNumero,
                    ip.adresse_chantier AS primeAdresseChantier,
                    ip.complement_chantier AS primeComplementChantier,
                    ip.code_postal_chantier AS primeCodePostalChantier,
                    ip.ville_chantier AS primeVilleChantier,
                    ip.index_prime AS primeIndex,
                    ip.onglet AS primeOnglet,
                    ip.modele_id AS modeleId,
                    il.numero AS lotNumero,
                    CASE WHEN ('' != DATE_FORMAT(il.date_statut_8, '%d/%m/%Y'))
                        THEN DATE_FORMAT(il.date_statut_8, '%d/%m/%Y')
                        ELSE 'XX/XX/XXXX'
                    END AS lotDateStatut8,
                    ci.titre_dispositif AS clientTitreDispositif
            FROM import_lot il
                INNER JOIN import_canal ic ON ic.lot_id = il.id
                INNER JOIN import_prime ip ON ip.canal_id = ic.id
                INNER JOIN client_ c ON c.id = il.client_id
                INNER JOIN client_information ci ON ci.id = c.client_information_id
            WHERE il.client_id = " . $clientId . " 
                AND il.id = " . $lotId . "
            ORDER BY ip.index_prime ASC
        ";

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
