<?php

namespace AppBundle\Service\Printers;


use AppBundle\Repository\ModelRepository;

class ModelService implements ModelServiceInterface
{
    private $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    public function findAll()
    {
        return $this->modelRepository->findAll();
    }
    public function findOneById($id)
    {
        return $this->modelRepository->find($id);
    }
}
