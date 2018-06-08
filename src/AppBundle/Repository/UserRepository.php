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
                'SELECT c FROM AppBundle:User c WHERE c.ustudent = true ORDER BY c.name ASC'
            )
            ->getResult();
    }

    public function findAllSpeakers()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM AppBundle:User c WHERE c.title IS NOT NULL ORDER BY c.name ASC'
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

    public function findUsersbyDate($date)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u WHERE u.createdAt >= :date ORDER BY u.createdAt ASC')
            ->setParameter('date', $date)
            ->getResult();
    }

    public function countStudents()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(u.id)  FROM AppBundle:User u WHERE u.student = true'
            )
            ->getSingleScalarResult();
    }

    public function countTalks()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(u.id)  FROM AppBundle:User u WHERE u.title IS NOT NULL'
            )
            ->getSingleScalarResult();
    }

    public function countPosters()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(u.id)  FROM AppBundle:User u WHERE u.poster IS NOT NULL'
            )
            ->getSingleScalarResult();
    }

    public function countDinner()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT sum(u.dinner)  FROM AppBundle:User u'
            )
            ->getSingleScalarResult();
    }

    public function countMorelia()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT sum(u.morelia)  FROM AppBundle:User u'
            )
            ->getSingleScalarResult();
    }

    public function countPatzcuaro()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT sum(u.patzcuaro)  FROM AppBundle:User u'
            )
            ->getSingleScalarResult();
    }

}

