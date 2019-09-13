<?php

namespace AppBundle\Service\Printers;

use AppBundle\Entity\Model;
use Symfony\Component\Form\FormInterface;

interface ModelServiceInterface
{

    public function findAll(): array;

    public function findOneById($id): ?Model;

    public function add(Model $model, FormInterface $form): bool;

    public function edit(Model $model, FormInterface $form): bool;

    public function delete(int $id): bool;

    public function findAllDESC():array;
}
