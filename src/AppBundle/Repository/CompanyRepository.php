<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Company;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * CompanyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $metadata = null)
    {
        parent::__construct($em,
            $metadata == null ?
                new ClassMetadata(Company::class) :
                $metadata
        );
    }

    /**
     * @param Company $company
     * @return bool
     * @throws ORMException
     */
    public function add(Company $company)
    {
        try {
            $this->_em->persist($company);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param Company $company
     * @return bool
     * @throws ORMException
     */
    public function delete(Company $company)
    {
        try {
            $this->_em->remove($company);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param Company $company
     * @return bool
     * @throws ORMException
     */
    public function update(Company $company)
    {
        try {
            $this->_em->merge($company);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param $keyword
     * @return array
     */
    public function getByKeyword($keyword): array
    {
        return $this->createQueryBuilder('c')
            ->where("c.name LIKE '%$keyword%'")
            ->orderBy('c.dateAdded', "ASC")
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_OBJECT);
    }
}
