<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersAndCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory()->count(5)->create()->each(function ($company) {
            $users = User::factory()->count(5)->create();

            $company->users()->attach($users->pluck('id')->toArray());
        });
    }
}
