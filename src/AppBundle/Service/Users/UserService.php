<?php


namespace AppBundle\Service\Users;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Common\DateTimeServiceInterface;
use AppBundle\Service\Common\FileUploaderService;
use AppBundle\Service\Encryption\ArgonEncryption;
use AppBundle\Service\Roles\RoleServiceInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method getDoctrine()
 */
class UserService implements UserServiceInterface
{

    private $security;
    private $userRepository;
    private $encryptionService;
    private $roleService;
    private $dateTimeService;

    public function __construct(Security $security,
                                UserRepository $userRepository,
                                ArgonEncryption $encryptionService,
                                RoleServiceInterface $roleService,
                                DateTimeServiceInterface $dateTimeService)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->encryptionService = $encryptionService;
        $this->roleService = $roleService;
        $this->dateTimeService = $dateTimeService;
    }

    /**
     * @param string $email
     * @return object|User|null
     */
    public function findOneByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    /**
     * @param User $user
     * @return bool
     * @throws ORMException
     */
    public function save(User $user): bool
    {
        $passwordHash = $this->encryptionService->hash($user->getPassword());
        $user->setPassword($passwordHash);

        $user->setDateAdded($this->dateTimeService->setDateTimeNow());

        $userRole = $this->roleService->findOneBy("ROLE_USER");
        $user->addRole($userRole);

        return $this->userRepository->insert($user);
    }

    /**
     * @param int $id
     * @return object|User|null
     */
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param User $user
     * @return object|User|null
     */
    public function findOne(User $user): ?User
    {
        return $this->userRepository->find($user);
    }

    /**
     * @return User|UserInterface|null
     */
    public function currentUser(): ?User
    {
        return $this->security->getUser();
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }


    /**
     * @param $criteria
     * @return User[]
     */
    public function findAllUsersByRole($criteria): array
    {
        return $this->userRepository->getByRoleName($criteria);
    }

    /**
     * @return array
     */
    public function getAccountsPhones(): array
    {
        return $this->userRepository->getAccountsPhones();
    }

    /**
     * @return array
     */
    public function getEmployeesPhones(): array
    {
        return $this->userRepository->getEmployeePhones();
    }

}
