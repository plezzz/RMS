<?php


namespace AppBundle\Service\Search;

use AppBundle\Repository\CompanyRepository;
use AppBundle\Repository\ModelRepository;
use AppBundle\Repository\PrinterRepository;
use AppBundle\Repository\UserRepository;

class SearchService implements SearchServiceInterface
{
    private $printerRepository;
    private $userRepository;
    private $companyRepository;
    private $modelRepository;

    public function __construct(PrinterRepository $printerRepository,
                                UserRepository $userRepository,
                                CompanyRepository $companyRepository,
                                ModelRepository $modelRepository)
    {
        $this->printerRepository = $printerRepository;
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
        $this->modelRepository = $modelRepository;
    }

    public function searchResult(string $keyword)
    {
        $printers = $this->printerRepository->getByKeyword($keyword);
        $users = $this->userRepository->getByKeyword($keyword);
        $companies = $this->companyRepository->getByKeyword($keyword);
        $models = $this->modelRepository->getByKeyword($keyword);

        return $allData = [$printers, $users, $companies, $models];
    }
}
