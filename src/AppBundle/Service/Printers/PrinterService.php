<?php

namespace AppBundle\Service\Printers;

use AppBundle\Entity\Printer;
use AppBundle\Repository\PrinterRepository;
use AppBundle\Service\Company\CompanyServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Doctrine\ORM\ORMException;

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
     * PrinterService constructor.
     * @param PrinterRepository $printerRepository
     * @param UserServiceInterface $userService
     * @param StatusServiceInterface $statusService
     * @param ModelServiceInterface $modelService
     * @param CompanyServiceInterface $companyService
     */
    public function __construct(PrinterRepository $printerRepository,
                                UserServiceInterface $userService,
                                StatusServiceInterface $statusService,
                                ModelServiceInterface $modelService,
                                CompanyServiceInterface $companyService)
    {
        $this->printerRepository = $printerRepository;
        $this->userService = $userService;
        $this->statusService = $statusService;
        $this->modelService = $modelService;
        $this->companyService = $companyService;
    }

    /**
     * @param Printer $printer
     * @return bool
     * @throws ORMException
     * @throws \Exception
     */
    public function add(Printer $printer): bool
    {
        if($printer->getTechnician()===null){
            $technician=$this->userService->currentUser();
            $printer->setTechnician($technician);
        }

        if ($printer->getPrinterStatus()=='Ready')
            $printer->setDateFinish($printer->setFirstDateAdded());

        if ($printer->getSerialNumber() === null)
            $printer->setSerialNumber(1);

        return $this->printerRepository->add($printer);
    }

    public function edit(Printer $printer): bool
    {
        // TODO: Implement edit() method.
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
        $technicians = $this->userService->findAllTechnician();
        $printerLast = $this->findLastAdded();

        return $allData = [$models, $status, $companies,$technicians, $printerLast];
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
