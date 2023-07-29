<?php

namespace App\Interfaces;

use App\Models\Vehicle;

interface VehicleTypeDtoInterface
{
    public function buildDetail(array $data):VehicleTypeDtoInterface;
    public function toArray():array ;
    public function toJson():string ;
    public function hasValid():bool;
    
}