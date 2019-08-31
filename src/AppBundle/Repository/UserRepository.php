<?php

namespace AppBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;
use AppBundle\Entity\User;
use Doctrine\ORM\ORMException;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $metadata = null)
    {
        parent::__construct($em,
            $metadata == null ?
                new ClassMetadata(User::class) :
                $metadata
        );
    }

    /**
     * @param User $user
     * @return bool
     * @throws ORMException
     */
    public function insert(User $user)
    {
        try {

            $this->_em->persist($user);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getByRoleName(string $name)
    {
        return $this->createQueryBuilder('u')
            ->addSelect('r.name')
            ->innerJoin('u.roles','r')
            ->where('r.title=:name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $keyword
     * @return array
     */
    public function getByKeyword($keyword): array
    {
        return $this->createQueryBuilder('u')
            ->where("u.email LIKE '%$keyword%'")
            ->innerJoin('u.companyName', 'c')
            ->orWhere("u.username LIKE '%$keyword%'")
            ->orWhere("u.fullName LIKE '%$keyword%'")
            ->orWhere("c.name LIKE '%$keyword%'")
            ->orderBy('u.dateAdded', "ASC")
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }

    /**
     * @return array
     */
    public function getAccountsPhones(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.fullName, u.email, u.externalPhone')
            ->innerJoin('u.roles', 'r')
            ->where("r.title = 'Account'")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function getEmployeePhones(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.fullName, u.email, u.externalPhone, u.extensionPhone, u.mobilePhone, r.title')
            ->innerJoin('u.roles', 'r')
            ->where("r.title = 'Account'")
            ->orWhere("r.title = 'Technician'")
            ->orWhere("r.title = 'Admin'")
            ->orderBy('r.title','asc')
            ->orderBy('u.fullName','asc')
            ->getQuery()
            ->getResult();
    }
}
