<?php


namespace AppBundle\Service\Users;


use AppBundle\Entity\User;

interface UserServiceInterface
{
    public function findOneByEmail(string $email): ?User;

    public function save(User $user): bool;

    public function findOneById(int $id): ?User;

    public function findOne(User $user): ?User;

    public function currentUser(): ?User;

    public function findAll(): array;

    public function findAllUsersByRole($criteria): array;

    public function getAccountsPhones(): array;

    public function getEmployeesPhones(): array;
}
