<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use AppBundle\Entity\Printer;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * PrinterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrinterRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $metadata = null)
    {
        parent::__construct($em,
            $metadata == null ?
                new ClassMetadata(Printer::class) :
                $metadata
        );
    }

    /**
     * @param Printer $printer
     * @return bool
     * @throws ORMException
     */
    public function add(Printer $printer)
    {
        try {

            $this->_em->persist($printer);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }

    /**
     * @param Printer  $printer
     * @return bool
     * @throws ORMException
     */
    public function delete(Printer $printer)
    {
        try {

            $this->_em->remove($printer);
            $this->_em->flush();
            return true;
        } catch (OptimisticLockException $e) {
            return false;
        }
    }
}
