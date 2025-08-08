<?php

namespace Database\Seeders;

use App\Models\Ship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ships = [
            [
                'name' => 'Ocean Explorer',
                'address' => '123 Harbor Drive, Port City, PC 12345',
                'ship' => 'Cargo Vessel'
            ],
            [
                'name' => 'Sea Voyager',
                'address' => '456 Marina Way, Coastal Town, CT 67890',
                'ship' => 'Passenger Ferry'
            ],
            [
                'name' => 'Maritime Star',
                'address' => '789 Dock Street, Seaport, SP 11111',
                'ship' => 'Container Ship'
            ],
            [
                'name' => 'Blue Horizon',
                'address' => '321 Pier Road, Bay City, BC 22222',
                'ship' => 'Fishing Vessel'
            ],
            [
                'name' => 'Golden Wave',
                'address' => '654 Wharf Avenue, Harbor Town, HT 33333',
                'ship' => 'Luxury Yacht'
            ]
        ];

        foreach ($ships as $ship) {
            Ship::create($ship);
        }
    }
}
