<?php

namespace AppBundle\Service\Printers;

interface ModelServiceInterface
{
    public function findAll();
    public function findOneById($id);
}
