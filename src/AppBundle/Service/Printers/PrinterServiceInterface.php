<?php


namespace AppBundle\Service\Printers;

use AppBundle\Entity\Printer;
use Symfony\Component\HttpFoundation\Request;

interface PrinterServiceInterface
{
    public function add(Printer $printer): bool;
    public function edit(Printer $printer): bool;
    public function delete(int $id): bool;
    public function getData():array;
    public function findAll():array;
    public function findAllDESC():array;
    public function findAllBySerialNumber(string $serialNumber): array;
    public function findAllByTechnician(): array;
    public function findAllByCompany(): array;
    public function findLastAdded(): ?Printer;
    public function findOneByID(int $id): ?Printer;


}
