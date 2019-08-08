<?php

namespace AppBundle\Service\Printers;

use AppBundle\Entity\Model;

interface ModelServiceInterface
{
    public function findAll();
    public function findOneById($id);
    public function add(Model $model): bool;
}
