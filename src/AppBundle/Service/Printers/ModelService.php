<?php

namespace AppBundle\Service\Printers;


use AppBundle\Entity\Model;
use AppBundle\Repository\ModelRepository;
use AppBundle\Service\Common\DateTimeServiceInterface;
use AppBundle\Service\Common\FileUploaderService;
use AppBundle\Service\Users\UserServiceInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;

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
     * @var FileUploaderService
     */
    private $fileUploadService;

    /**
     * @var string
     * path for image
     */
    private $type = 'models';

    /**
     * ModelService constructor.
     * @param ModelRepository $modelRepository
     * @param DateTimeServiceInterface $dateTimeService
     * @param UserServiceInterface $userService
     * @param FileUploaderService $fileUploaderService
     */
    public function __construct(ModelRepository $modelRepository,
                                DateTimeServiceInterface $dateTimeService,
                                UserServiceInterface $userService,
                                FileUploaderService $fileUploaderService)
    {
        $this->modelRepository = $modelRepository;
        $this->dateTimeService = $dateTimeService;
        $this->userService = $userService;
        $this->fileUploadService = $fileUploaderService;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->modelRepository->findAll();
    }

    /**
     * @param $id
     * @return Model|object|null
     */
    public function findOneById($id): ?Model
    {
        return $this->modelRepository->find($id);
    }

    /**
     * @param Model $model
     * @param FormInterface $form
     * @return bool
     * @throws ORMException
     */
    public function add(Model $model, FormInterface $form): bool
    {
        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile,$this->type);
            $model->setImage($imageFile);
        }else{
            $model->setImage('defaultCompanyImage.png');
        }

        $model->setUserAdded($this->userService->currentUser());
        $model->setDateAdded($this->dateTimeService->setDateTimeNow());
        return $this->modelRepository->add($model);
    }

    /**
     * @param Model $model
     * @param FormInterface $form
     * @return bool
     * @throws ORMException
     */
    public function edit(Model $model, FormInterface $form): bool
    {
        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile, $this->type);
            $model->setImage($imageFile);
        }

        return $this->modelRepository->update($model);
    }

    /**
     * @param int $id
     * @return bool
     * @throws ORMException
     */
    public function delete(int $id): bool
    {
        $model = $this->findOneByID($id);
        return $this->modelRepository->delete($model);
    }
}
