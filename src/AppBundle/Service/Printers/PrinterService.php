<?php


namespace AppBundle\Service\Printers;


use AppBundle\Entity\Printer;
use AppBundle\Repository\PrinterRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\NonUniqueResultException;

class PrinterService implements PrinterServiceInterface
{
    /**
     * @var Printer
     */
    private $printerRepository;

    public function __construct(PrinterRepository $printerRepository)
    {
        $this->printerRepository = $printerRepository;
    }

    public function create(Printer $printer): bool
    {
        // TODO: Implement create() method.
    }

    public function edit(Printer $printer): bool
    {
        // TODO: Implement edit() method.
    }

    public function delete(Printer $printer): bool
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param string $serialNumber
     * @return array
     */
    public function findAllBySerialNumber(string $serialNumber)
    {
        return $this->printerRepository->findBy(['serialNumber' => $serialNumber], ['dateAdded'=>'DESC']);
    }

    public function findOneByID(int $id)
    {
        return $this->printerRepository->findBy(['id' => $id]);
    }

    public function findAll()
    {
        return $this->printerRepository->findAll();
    }

    public function findAllDESC()
    {
        return $this->printerRepository->findBy([],['id'=>'desc']);
    }

    public function findLastAdded()
    {
            return $this->printerRepository->findBy([],['id'=>'desc'],1);
    }
}
