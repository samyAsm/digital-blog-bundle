<?php

namespace DhiBlogBundle\Repository;

use DhiBlogBundle\Core\Repository\CoreRepository;
use DhiBlogBundle\Entity\Author;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends CoreRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param $credentials
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByCredentials($credentials)
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere('a.phone_number = :credential OR a.email = :credential')
            ->setParameter('credential', $credentials)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $token
     * @return Author|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByToken($token)
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere('a.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $token
     * @return Author|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByResetPasswordToken($token)
    {
        return $this->getQueryBuilderWithUnDeleted()
            ->andWhere('a.reset_password_token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
