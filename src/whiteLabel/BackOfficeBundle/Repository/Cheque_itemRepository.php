<?php

namespace whiteLabel\BackOfficeBundle\Repository;

/**
 * Cheque_itemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Cheque_itemRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $clientId
     * @return array
     */
    public function findByClient($clientId)
    {
        $EM = $this->getEntityManager('EM_MYSQL');

        $query = "
            SELECT  cs.id AS stockId,
                    cs.reference_boite AS stockReferenceBoite,
                    cb.nom AS banqueNom,
                    ci.numero AS chequeNumero,
                    ci.date_creation AS dateCreation,
                    ci.auteur_creation AS auteurCreation
            FROM cheque_item ci
                INNER JOIN cheque_stock cs ON cs.id = ci.stock_id
                INNER JOIN client_banque cb ON cb.id = cs.banque_id
            WHERE cs.client_id = " . $clientId . "
            ORDER BY ci.numero DESC
        ";

        $stmt = $EM
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param $clientId
     * @param $stockId
     * @return array
     */
    public function findByStock($clientId, $stockId)
    {
        $EM = $this->getEntityManager('EM_MYSQL');

        $query = "
            SELECT  cs.id AS stockId,
                    cs.reference_boite AS stockReferenceBoite,
                    cb.nom AS banqueNom,
                    ci.numero AS chequeNumero,
                    ci.date_creation AS dateCreation,
                    ci.auteur_creation AS auteurCreation
            FROM cheque_item ci
                INNER JOIN cheque_stock cs ON cs.id = ci.stock_id
                INNER JOIN client_banque cb ON cb.id = cs.banque_id
            WHERE cs.client_id = " . $clientId . "
                AND ci.stock_id = " . $stockId . "
            ORDER BY ci.numero DESC
        ";

        $stmt = $EM
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
