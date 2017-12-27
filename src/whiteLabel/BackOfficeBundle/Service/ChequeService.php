<?php

namespace whiteLabel\BackOfficeBundle\Service;

use whiteLabel\BackOfficeBundle\Entity\Cheque_item;

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
     * @param $first
     * @param $last
     * @return array
     */
    public function createChequeItem($first, $last)
    {
        $first = (int)$first;
        $last = (int)$last;
        $delta_increment = ($last - $first) + $first;

        $arrayCheque = array();
        for ($i=$first; $i<=$delta_increment; $i++) {
            $objectCheque = new Cheque_item();
            $objectCheque->setNumero($this->formatNumeroCheque($i));

            $arrayCheque[] = $objectCheque;
        }

        return $arrayCheque;
    }

    /**
     * @param $listCheque
     * @param $stockId
     */
    public function updateStockId($listCheque, $stockId)
    {
        $EM = $this->doctrine->getManager();

        foreach ($listCheque as $item) {
            $query = "
            UPDATE cheque_item
            SET stock_id = " . $stockId . "
            WHERE id = " . $item->getId();

            $stmt = $EM
                ->getConnection()
                ->prepare($query);
            $stmt->execute();
        }
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
