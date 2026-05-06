<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'user_id' => (string) Str::uuid(),
                'role' => 'admin',
                'password_hash' => Hash::make('admin12345'),
            ]
        );
        $admin->forceFill([
            'role' => 'admin',
            'password_hash' => Hash::make('admin12345'),
        ])->save();

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@hotel.com'],
            [
                'user_id' => (string) Str::uuid(),
                'role' => 'staff',
                'password_hash' => Hash::make('staff12345'),
            ]
        );
        $staffUser->forceFill([
            'role' => 'staff',
            'password_hash' => Hash::make('staff12345'),
        ])->save();

        $staff = Staff::firstOrCreate(
            ['user_id' => $staffUser->user_id],
            [
                'staff_id' => (string) Str::uuid(),
                'first_name' => 'Hotel',
                'last_name' => 'Staff',
                'role' => 'Front Desk',
                'shift' => 'morning',
                'is_available' => true,
            ]
        );
        $staff->forceFill([
            'first_name' => 'Hotel',
            'last_name' => 'Staff',
            'role' => 'Front Desk',
            'shift' => 'morning',
            'is_available' => true,
        ])->save();
    }
}
