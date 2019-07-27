<?php

namespace AppBundle\Service\Printers;

interface StatusServiceInterface
{
    public function findAll();
    public function findOneById($id);
    public function findOneBy(string $criteria);
}
