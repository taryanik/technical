<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Collection;

class CompanyService
{
    protected CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }
    public function getCompaniesByUser(User $user): Collection
    {
        return $this->companyRepository->findByUser($user);
    }

    public function createCompanyForUser(User $user, array $data): Company
    {
        $company = $this->companyRepository->create($data);
        $user->companies()->save($company);

        return $company;
    }
}
