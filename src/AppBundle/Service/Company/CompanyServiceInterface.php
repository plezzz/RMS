<?php


namespace AppBundle\Service\Company;


interface CompanyServiceInterface
{
    public function findAll();
    public function findOneById($id);
}
