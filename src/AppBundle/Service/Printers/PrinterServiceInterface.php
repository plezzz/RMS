<?php


namespace AppBundle\Service\Printers;

use AppBundle\Entity\Printer;

interface PrinterServiceInterface
{
    public function create(Printer $printer): bool;
    public function edit(Printer $printer): bool;
    public function delete(Printer $printer): bool;
}
