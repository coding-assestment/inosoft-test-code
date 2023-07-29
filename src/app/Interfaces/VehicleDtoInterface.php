<?php

namespace App\Interfaces;

use App\Models\Vehicle;

interface VehicleDtoInterface
{
    public function createVehicle(array $data):VehicleDtoInterface;
    public function setVehicleType(VehicleTypeDtoInterface $detail):VehicleDtoInterface;
    public function getVehicleType():VehicleTypeDtoInterface;
    public function toArray():array ;
    public function toJson():string;
}