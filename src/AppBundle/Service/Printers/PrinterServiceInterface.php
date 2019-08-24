<?php


namespace AppBundle\Service\Printers;

use AppBundle\Entity\Printer;
use Symfony\Component\Form\FormInterface;

interface PrinterServiceInterface
{
    public function add(Printer $printer,FormInterface $form): bool;
    public function edit(Printer $printer,FormInterface $form): bool;
    public function delete(int $id): bool;
    public function getData():array;
    public function findAll():array;
    public function findAllDESC():array;
    public function findAllBySerialNumber(string $serialNumber): array;
    public function findAllByTechnician(): array;
    public function findAllByCompanyId(int $id): array;
    public function findAllByCompanyForCurrentUser(): array;
    public function findLastAdded(): ?Printer;
    public function findOneByID(int $id): ?Printer;


}
