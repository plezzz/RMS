<?php

namespace AppBundle\Service\Printers;

use AppBundle\Entity\Printer;
use AppBundle\Repository\PrinterRepository;
use AppBundle\Service\Common\DateTimeServiceInterface;
use AppBundle\Service\Common\FileUploaderService;
use AppBundle\Service\Company\CompanyServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;

class PrinterService implements PrinterServiceInterface
{
    /**
     * @var Printer
     */
    private $printerRepository;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var StatusServiceInterface
     */
    private $statusService;

    /**
     * @var ModelServiceInterface
     */
    private $modelService;

    /**
     * @var CompanyServiceInterface
     */
    private $companyService;

    /**
     * @var DateTimeServiceInterface
     */
    private $dateTimeService;

    /**
     * @var FileUploaderService
     */
    private $fileUploadService;

    /**
     * @var string
     * path for image
     */
    private $type = 'clientPrinters';

    /**
     * PrinterService constructor.
     * @param PrinterRepository $printerRepository
     * @param UserServiceInterface $userService
     * @param StatusServiceInterface $statusService
     * @param ModelServiceInterface $modelService
     * @param CompanyServiceInterface $companyService
     * @param DateTimeServiceInterface $dateTimeService
     * @param FileUploaderService $fileUploaderService
     */
    public function __construct(PrinterRepository $printerRepository,
                                UserServiceInterface $userService,
                                StatusServiceInterface $statusService,
                                ModelServiceInterface $modelService,
                                CompanyServiceInterface $companyService,
                                DateTimeServiceInterface $dateTimeService,
                                FileUploaderService $fileUploaderService)
    {
        $this->printerRepository = $printerRepository;
        $this->userService = $userService;
        $this->statusService = $statusService;
        $this->modelService = $modelService;
        $this->companyService = $companyService;
        $this->dateTimeService = $dateTimeService;
        $this->fileUploadService = $fileUploaderService;
    }

    /**
     * @param Printer $printer
     * @param FormInterface $form
     * @return bool
     * @throws ORMException
     */
    public function add(Printer $printer,FormInterface $form): bool
    {
        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile, $this->type);
            $printer->setImage($imageFile);
        }

        if ($printer->getTechnician() === null) {
            $technician = $this->userService->currentUser();
            $printer->setTechnician($technician);
        }

        $printer->setDateAdded($this->dateTimeService->setDateTimeNow());
        $printer->setDateFinish($this->dateTimeService->setDateTimeBlank());

        if ($printer->getPrinterStatus() == 'Ready')
            $printer->setDateFinish($this->dateTimeService->setDateTimeNow());

        if ($printer->getSerialNumber() === null)
            $printer->setSerialNumber(1);

        return $this->printerRepository->add($printer);
    }

    /**
     * @param Printer $printer
     * @param FormInterface $form
     * @return bool
     * @throws ORMException
     */
    public function edit(Printer $printer,FormInterface $form): bool
    {
        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile, $this->type);
            $printer->setImage($imageFile);
        }

        if ($printer->getPrinterStatus() == 'Ready')
            $printer->setDateFinish($this->dateTimeService->setDateTimeNow());

        return $this->printerRepository->update($printer);
    }

    /**
     * @param int $id
     * @return bool
     * @throws ORMException
     */
    public function delete(int $id): bool
    {
        $printer = $this->findOneByID($id);
        return $this->printerRepository->delete($printer);
    }

    /**
     * @param string $serialNumber
     * @return array
     */
    public function findAllBySerialNumber(string $serialNumber): array
    {
        return $this->printerRepository->findBy(['serialNumber' => $serialNumber], ['dateAdded' => 'DESC']);
    }

    /**
     * @param int $id
     * @return Printer|null
     */
    public function findOneByID(int $id): ?Printer
    {
        return $this->printerRepository->find(['id' => $id]);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->printerRepository->findAll();
    }

    /**
     * @return array
     */
    public function findAllDESC(): array
    {
        return $this->printerRepository->findBy([], ['id' => 'desc']);
    }

    /**
     * @return Printer|null
     */
    public function findLastAdded(): ?Printer
    {
        $printer = $this->printerRepository->findBy([], ['id' => 'desc'], 1);
        return $printer[0];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $models = $this->modelService->findAll();
        $status = $this->statusService->findAll();
        $companies = $this->companyService->findAll();
        $technicians = $this->userService->findAllUsersByRole('Technician');
        $printerLast = $this->findLastAdded();
        return $allData = [$models, $status, $companies, $technicians, $printerLast];
    }

    /**
     * @return array
     */
    public function findAllByTechnician(): array
    {
        $technician = $this->userService->currentUser();
        return $this->printerRepository->findBy(['technician' => $technician], ['dateAdded' => 'DESC']);
    }

    /**
     * @return array
     */
    public function findAllByCompany(): array
    {
        $companyId = $this->userService->currentUser()->getCompanyName()->getId();
        return $this->printerRepository->findBy(['companyName' => $companyId], ['dateAdded' => 'DESC']);
    }
}
