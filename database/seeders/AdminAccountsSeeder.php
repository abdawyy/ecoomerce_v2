<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminAccountsSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        Admin::query()->delete();
        Schema::enableForeignKeyConstraints();

        Admin::query()->create([
            'username' => 'hema997',
            'email' => 'hema997@truelabelegypt.com',
            'password' => Hash::make('ChangeMe!'),
            'is_active' => true,
            'is_hidden' => false,
        ]);

        Admin::query()->create([
            'username' => 'seeing',
            'email' => 'seeing@issue.com',
            'password' => Hash::make('235689Me!'),
            'is_active' => true,
            'is_hidden' => true,
        ]);
    }
}
