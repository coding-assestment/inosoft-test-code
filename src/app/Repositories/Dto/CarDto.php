<?php 
namespace App\Repositories\Dto;

use App\Interfaces\VehicleTypeDtoInterface;
use App\Models\Vehicle;

class CarDto  implements VehicleTypeDtoInterface
{
    protected array $detail = [];
    protected bool $isValid = false;

    public function buildDetail(array $data):VehicleTypeDtoInterface{
        $this->isValid = ($data['type']??false)===Vehicle::CAR_TYPE;
        
        $this->detail = [
            'passanger_capacity' => $data['passanger_capacity']??'',
            'car_type' => $data['car_type']??'',
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
