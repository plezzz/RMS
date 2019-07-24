<?php


namespace AppBundle\Service\Roles;


interface RoleServiceInterface
{
    public function findOneBy(string $criteria);
}
