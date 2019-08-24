<?php


namespace AppBundle\Service\Company;


use AppBundle\Entity\Company;
use Symfony\Component\Form\FormInterface;

interface CompanyServiceInterface
{
    public function findAll(): array;
    public function findOneById($id): ?Company;
    public function add(Company $company, FormInterface $form): bool;
    public function edit(Company $company, FormInterface $form): bool;
    public function delete(int $id): bool;
}
