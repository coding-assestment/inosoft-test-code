<?php

namespace App\Repositories\Dto;

use App\Interfaces\VehicleDtoInterface;
use App\Interfaces\VehicleTypeDtoInterface;
use App\Models\Vehicle;
use InvalidArgumentException;

class VehicleDto implements VehicleDtoInterface
{

    protected array $data = [];
    protected VehicleTypeDtoInterface $vehicleType;
    protected array $vehicleDetailMembers = [
        CarDto::class,
        MotorDto::class
    ];

    public function createVehicle(array $data = []):VehicleDtoInterface {
        $price = (int) ($data['price']??0);
        
        if ( $price <= 0 ) {
            throw new InvalidArgumentException("Invalid Vehicle Price", 1);
        }

        $name = $data['name'];
        if ( strlen($name) == 0 ) {
            throw new InvalidArgumentException("Invalid Vehicle Name", 1);
        }

        $this->data = [
            'name' => $name,
            'production_year' => $data['production_year']??date("Y"),
            'price' => $price,
            'qty_in_stock' => $data['qty_in_stock']??0,
            'qty_out_stock' => $data['qty_out_stock']??0,
            'qty_hold' => $data['qty_hold']??0,
            'qty_total' => $data['qty_total']??0,
            'type' =>  $data['type']??'',
        ];
        
        $this->readVehicleTypes($data);
        
        return $this;
    }

    public function setVehicleType(VehicleTypeDtoInterface $detail):VehicleDtoInterface {
        
        if ($detail->hasValid()) {
            $this->vehicleType = $detail;
            $this->data['detail'] = $detail->toArray();
        }
        return $this;
    }

    public function getVehicleType():VehicleTypeDtoInterface {
        return $this->vehicleType;
    }
    
    private function readVehicleTypes(array $data = []):void {
        
        foreach ($this->vehicleDetailMembers as  $vehicleMember) {
            $this->setVehicleType((new $vehicleMember)->buildDetail($data));
        }
        if (empty($this->vehicleType) ) {
            throw new InvalidArgumentException("Invalid Vehicle Type", 1);
        }
    }

    public function toArray():array {
        return $this->data;
    }

    public function toJson():string {
        return json_encode($this->data);
    }
}