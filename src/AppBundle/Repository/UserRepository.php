<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllStudents()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM AppBundle:User u WHERE u.ustudent = true ORDER BY u.name ASC'
            )
            ->getResult();
    }

    public function findAllSpeakers()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM AppBundle:User u WHERE u.title = true ORDER BY u.name ASC'
            )
            ->getResult();
    }

    public function findAllPayment()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM AppBundle:User u WHERE u.title = true ORDER BY u.name ASC'
            )
            ->getResult();
    }
}

