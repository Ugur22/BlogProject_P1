<?php

namespace AppBundle\Repositories;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function findUser($search)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where($qb->expr()->like('u.firstname', ':search'))
            ->orWhere($qb->expr()->like('u.surname', ':search'))
            ->orWhere($qb->expr()->like('u.email', ':search'))
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('u.firstname');

        return $qb->getQuery()->getResult();

    }


    public function findAllUser()
    {
        $em = $this->getEntityManager();
        $user = $em->getRepository('AppBundle:User')
            ->findBy(array(), array('firstname' => 'ASC'));

        return $user;
    }


}
