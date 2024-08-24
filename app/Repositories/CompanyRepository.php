<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Collection;

class CompanyRepository
{
    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function findByUser(User $user): Collection
    {
        return $user->companies;
    }
}
