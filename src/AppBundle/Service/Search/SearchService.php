<?php


namespace AppBundle\Service\Search;

use AppBundle\Repository\CompanyRepository;
use AppBundle\Repository\PrinterRepository;
use AppBundle\Repository\UserRepository;

class SearchService implements SearchServiceInterface
{
    private $printerRepository;
    private $userRepository;
    private $companyRepository;

    public function __construct(PrinterRepository $printerRepository,
                                UserRepository $userRepository,
                                CompanyRepository $companyRepository)
    {
        $this->printerRepository = $printerRepository;
        $this->userRepository=$userRepository;
        $this->companyRepository=$companyRepository;
    }

    public function searchResult(string $keyword)
    {
        $printers = $this->printerRepository->getByKeyword($keyword);
        $users = $this->userRepository->getByKeyword($keyword);
        $companies = $this->companyRepository->getByKeyword($keyword);

        return $allData = [$printers, $users, $companies];
    }
}
