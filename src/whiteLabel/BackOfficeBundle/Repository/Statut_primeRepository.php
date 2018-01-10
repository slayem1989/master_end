<?php

namespace whiteLabel\BackOfficeBundle\Repository;

/**
 * Statut_primeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Statut_primeRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $statutId
     * @return string
     */
    public function findSlugByStatut($statutId)
    {
        $value = '';

        $query = $this
            ->createQueryBuilder('s')
            ->select('s.slug')
        ;

        $query = $query
            ->where('s.id = :statutId')
            ->setParameters(array(
                'statutId' => $statutId
            ));

        $res = $query->getQuery()->getResult();

        if ($res && $res[0]) {
            $value = $res[0]['slug'];
        }

        return $value;
    }
}
