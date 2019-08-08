<?php

namespace AppBundle\Service\Printers;


use AppBundle\Entity\Model;
use AppBundle\Repository\ModelRepository;
use AppBundle\Service\Common\DateTimeServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Doctrine\ORM\ORMException;

class ModelService implements ModelServiceInterface
{
    /**
     * @var ModelRepository
     */
    private $modelRepository;
    /**
     * @var DateTimeServiceInterface
     */
    private $dateTimeService;
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * ModelService constructor.
     * @param ModelRepository $modelRepository
     * @param DateTimeServiceInterface $dateTimeService
     * @param UserServiceInterface $userService
     */
    public function __construct(ModelRepository $modelRepository,
                                DateTimeServiceInterface $dateTimeService,
                                UserServiceInterface $userService)
    {
        $this->modelRepository = $modelRepository;
        $this->dateTimeService = $dateTimeService;
        $this->userService = $userService;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->modelRepository->findAll();
    }

    /**
     * @param $id
     * @return object|null
     */
    public function findOneById($id)
    {
        return $this->modelRepository->find($id);
    }

    /**
     * @param Model $model
     * @return bool
     * @throws ORMException
     */
    public function add(Model $model): bool
    {
        if ($model->getImage() === null)
            $model->setImage('defaultModelImage.png');
        $model->setUserAdded($this->userService->currentUser());
        $model->setDateAdded($this->dateTimeService->setDateTimeNow());
            return $this->modelRepository->add($model);
    }
}
