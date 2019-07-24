<?php


namespace AppBundle\Service\Users;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Encryption\ArgonEncryption;
use AppBundle\Service\Encryption\EncryptionServiceInterface;
use AppBundle\Service\Roles\RoleServiceInterface;
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

    public function __construct(Security $security,
                              //  UserRepository $userRepository,
                                ArgonEncryption $encryptionService,
                               RoleServiceInterface $roleService)
    {
        $this->security = $security;
       // $this->userRepository = $userRepository;
        $this->encryptionService = $encryptionService;
        $this->roleService = $roleService;

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
     */
    public function save(User $user): bool
    {
        $passwordHash = $this->encryptionService->hash($user->getPassword());
        $user->setPassword($passwordHash);

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
}
