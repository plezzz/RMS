<?php


namespace AppBundle\Service\Company;


use AppBundle\Entity\Company;
use AppBundle\Repository\CompanyRepository;
use AppBundle\Service\Common\DateTimeServiceInterface;
use AppBundle\Service\Common\FileUploaderService;
use AppBundle\Service\Users\UserServiceInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;

class CompanyService implements CompanyServiceInterface
{
    const IMAGE_TYPE = 'company';

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var FileUploaderService
     */
    private $fileUploadService;

    /**
     * @var DateTimeServiceInterface
     */
    private $dateTimeService;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * CompanyService constructor.
     * @param CompanyRepository $companyRepository
     * @param FileUploaderService $fileUploaderService
     * @param DateTimeServiceInterface $dateTimeService
     * @param UserServiceInterface $userService
     */
    public function __construct(CompanyRepository $companyRepository,
                                FileUploaderService $fileUploaderService,
                                DateTimeServiceInterface $dateTimeService,
                                UserServiceInterface $userService)
    {
        $this->companyRepository = $companyRepository;
        $this->fileUploadService = $fileUploaderService;
        $this->dateTimeService = $dateTimeService;
        $this->userService = $userService;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->companyRepository->findAll();
    }

    /**
     * @param $id
     * @return Company|object|null
     */
    public function findOneById($id): ?Company
    {
        return $this->companyRepository->find($id);
    }

    /**
     * @param Company $company
     * @param FormInterface $form
     * @return bool
     * @throws ORMException
     */
    public function add(Company $company, FormInterface $form): bool
    {
        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile, self::IMAGE_TYPE);
            $company->setImage($imageFile);
        } else {
            $company->setImage('defaultCompanyImage.png');
        }

        $company->setUserAdded($this->userService->currentUser());
        $company->setDateAdded($this->dateTimeService->setDateTimeNow());
        return $this->companyRepository->add($company);
    }

    /**
     * @param Company $company
     * @param FormInterface $form
     * @return bool
     * @throws ORMException
     */
    public function edit(Company $company, FormInterface $form): bool
    {
        $imageFile = $form['image']->getData();
        if ($imageFile) {
            $imageFile = $this->fileUploadService->upload($imageFile, self::IMAGE_TYPE);
            $company->setImage($imageFile);
        }

        return $this->companyRepository->update($company);
    }

    /**
     * @param int $id
     * @return bool
     * @throws ORMException
     */
    public function delete(int $id): bool
    {
        $company = $this->findOneByID($id);
        return $this->companyRepository->delete($company);
    }
}
