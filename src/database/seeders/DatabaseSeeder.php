<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::truncate();
        \App\Models\User::factory(2)->create();

        $this->seedVehicles();
    }

    function seedVehicles(): void {
        
        \App\Models\Vehicle::truncate();
        \App\Models\VehicleTransaction::truncate();
        return ;
        \App\Models\Vehicle::factory(3)->create();

        $vehicles = \App\Models\Vehicle::get();
        foreach ($vehicles as $vehicle) {
            $vehicle->vehicleTransactions()->create([
                'qty'=> 10,
                'type_transaction' => 'in_stock', //['in_stock', 'out_stock', 'hold']
                'transaction_detail'=> '{}',//json
                'status' => 1 ,//[ 0=>'Failed', 1=> 'Success', 2=>'Cancel']
            ]);

            $vehicle->qty_in_stock = 10;
            $vehicle->qty_total = 10;
            $vehicle->save();
            $vehicle->load('vehicleTransactions');
        }

    }

}
