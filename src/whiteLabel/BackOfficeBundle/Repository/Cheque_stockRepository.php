<?php

namespace whiteLabel\BackOfficeBundle\Repository;

/**
 * Cheque_stockRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Cheque_stockRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Cheque_stockRepository constructor.
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
            SELECT  cs.id AS stockId,
                    cs.reference_boite AS stockReferenceBoite,
                    cs.first AS chequeFirst,
                    cs.last AS chequeLast,
                    cb.nom AS banqueNom
            FROM cheque_stock cs
                INNER JOIN client_banque cb ON cb.id = cs.banque_id
            WHERE cs.client_id = " . $clientId . "
            ORDER BY cs.reference_boite ASC
        ";

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param $stockId
     * @return mixed
     */
    public function findByStock($stockId)
    {
        $query = "
            SELECT  cs.id AS stockId,
                    cs.reference_boite AS stockReferenceBoite,
                    cs.first AS chequeFirst,
                    cs.last AS chequeLast,
                    cs.count AS chequeCount,
                    cs.date_reception AS stockDateReception,
                    cs.auteur_creation,
                    cb.nom AS banqueNom
            FROM cheque_stock cs
                INNER JOIN client_banque cb ON cb.id = cs.banque_id
            WHERE cs.id = " . $stockId . "
        ";

        $stmt = $this->_em
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetch();
    }
}
