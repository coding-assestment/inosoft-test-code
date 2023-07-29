<?php

namespace App\Interfaces;

interface VehicleTransactionDtoInterface
{
    public function createVehicleTransaction(int $qty, $vehicleId, int $typeTransaction):VehicleTransactionDtoInterface;
    public function calculateVehicleData(VehicleDtoInterface $vehicleDto):VehicleTransactionDtoInterface;
    public function getOldVehicleData():VehicleDtoInterface;
    public function getNewVehicleData():VehicleDtoInterface;
    public function toArray():array;
    public function toJson():string;
}