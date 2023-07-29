<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $pricelist = [1000, 2000, 3000, 4000, 5000, 6000];
        $indexPrice = array_rand($pricelist);
        $vehicleTypes = [Vehicle::MOTOR_TYPE, Vehicle::CAR_TYPE];
        $indexType = array_rand($vehicleTypes);
        return [
            'name'=> $this->faker->name().' Vechicle',
            'price' => $pricelist[$indexPrice],
            'qty_in_stock' => 10,
            'qty_out_stock' => 0,
            'qty_hold' => 0,
            'qty_total' => 10,
            'type' => $vehicleTypes[$indexType],
            'detail'=> '{}',
        ];
    }
}
