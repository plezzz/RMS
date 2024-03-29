<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;


/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $metadata = null)
    {
        parent::__construct($em,
            $metadata == null ?
                new ClassMetadata(Role::class) :
                $metadata
        );
    }
}
