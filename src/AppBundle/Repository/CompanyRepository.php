<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * CompanyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $metadata = null)
    {
        parent::__construct($em,
            $metadata == null ?
                new ClassMetadata(Company::class) :
                $metadata
        );
    }
}