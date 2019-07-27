<?php


namespace AppBundle\Service\Printers;


use AppBundle\Repository\StatusRepository;

class StatusService implements StatusServiceInterface
{
    private $statusRepository;

    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function findAll()
    {
        return $this->statusRepository->findAll();
    }
    public function findOneById($id)
    {
        return $this->statusRepository->find($id);
    }
    public function findOneBy(string $criteria)
    {
        return $this->statusRepository->findOneBy(
            ['name'=>$criteria]
        );
    }
}
