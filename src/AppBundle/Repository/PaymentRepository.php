<?php

namespace AppBundle\Repository;


/**
 * PaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaymentRepository extends \Doctrine\ORM\EntityRepository
{
    public function countPayments()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(p.id)  FROM AppBundle:Payment p'
            )
            ->getSingleScalarResult();
    }

}