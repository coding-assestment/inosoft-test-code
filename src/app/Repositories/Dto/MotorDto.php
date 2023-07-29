<?php 
namespace App\Repositories\Dto;

// use App\Interfaces\VehicleDtoAbstract;
use App\Interfaces\VehicleTypeDtoInterface;
use App\Models\Vehicle;

class MotorDto  implements VehicleTypeDtoInterface
{
    protected array $detail = [];
    protected bool $isValid = false;

    public function buildDetail(array $data):VehicleTypeDtoInterface{
        $this->isValid = ($data['type']??false)===Vehicle::MOTOR_TYPE;
        
        $this->detail = [
            'supenstion_type'=>$data['supenstion_type']??'',
            'transmission_type'=>$data['transmission_type']??'',
        ];
        return $this;
    }

    public function toArray():array {
        return $this->detail;
    }

    public function toJson():string {
        return json_encode($this->detail);
    }
    
    public function hasValid():bool{
        return $this->isValid;
    }
   
}
