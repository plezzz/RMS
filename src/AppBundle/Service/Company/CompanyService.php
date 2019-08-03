<?php


namespace AppBundle\Service\Company;


use AppBundle\Repository\CompanyRepository;

class CompanyService implements CompanyServiceInterface
{
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function findAll()
    {
        return $this->companyRepository->findAll();
    }

    public function findOneById($id)
    {
        return $this->companyRepository->find($id);
    }
}
