<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;
use App\Models\Device;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // =============================
        // 1. Create Admin User
        // =============================
        $admin = User::create([
            'name' => 'Admin NyingnyingTrack',
            'email' => 'admin@nyingnyingtrack.co',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // =============================
        // 2. Create Customers + Users
        // =============================
        $customersData = [
            [
                'user' => [
                    'name' => 'Customer A',
                    'email' => 'customerA@test.com',
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                ],
                'name' => 'PT. Alpha',
                'contact_person' => 'Budi',
                'phone' => '081234567890',
                'address' => 'Jakarta',
                'devices' => [
                    ['imei' => '356823045678901', 'name' => 'GT06 A1', 'model' => 'GT06', 'sim_number' => '081234567890'],
                    ['imei' => '356823045678902', 'name' => 'VT100 A1', 'model' => 'VT100', 'sim_number' => '081234567891'],
                ],
            ],
            [
                'user' => [
                    'name' => 'Customer B',
                    'email' => 'customerB@test.com',
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                ],
                'name' => 'PT. Beta',
                'contact_person' => 'Siti',
                'phone' => '081298765432',
                'address' => 'Bandung',
                'devices' => [
                    ['imei' => '356823045678903', 'name' => 'GT06 B1', 'model' => 'GT06', 'sim_number' => '081298765432'],
                    ['imei' => '356823045678904', 'name' => 'VT100 B1', 'model' => 'VT100', 'sim_number' => '081298765433'],
                ],
            ],
        ];

        foreach ($customersData as $cData) {
            $user = User::create($cData['user']);

            $customer = Customer::create([
                'user_id' => $user->id,
                'name' => $cData['name'],
                'contact_person' => $cData['contact_person'],
                'phone' => $cData['phone'],
                'address' => $cData['address'],
            ]);

            foreach ($cData['devices'] as $d) {
                Device::create(array_merge($d, ['customer_id' => $customer->id]));
            }
        }

        $this->command->info('MasterSeeder finished: Users, Customers, Devices created.');
    }
}
