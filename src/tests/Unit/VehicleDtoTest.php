<?php

namespace Tests\Unit;

use App\Interfaces\VehicleTypeDtoInterface;
use App\Models\Vehicle;
use App\Repositories\Dto\CarDto;
use App\Repositories\Dto\MotorDto;
use App\Repositories\Dto\VehicleDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VehicleDtoTest extends TestCase
{

    protected $templateData = [
        'name' => 'New Honda vario',
        'production_year' => 2023,
        'price' => 1000,
        'qty_in_stock' => 0,
        'qty_out_stock' => 0,
        'qty_hold' => 0,
        'qty_total' => 0,
        'type' =>  'uknown type', //unknown type
    ];
    /**
     * Invalid creational Vehicle type value string unit test should return ehicletypedtointerface.
     *
     * @return void
     */
    public function test_invalid_vehicle_dto_type_value_string_should_throw_invalidargumentexception()
    {
        $this->expectException(InvalidArgumentException::class);
        $dataVehicle = $this->templateData;
        $dataVehicle['type'] =  'unknown type'; //unknown type
        (new VehicleDto)->createVehicle( $dataVehicle ); 
    }
    
    /**
     * Invalid creational Vehicle type outrange 0,1 unit test should return ehicletypedtointerface.
     *
     * @return void
     */
    public function test_invalid_vehicle_dto_type_outrange_0_1_should_throw_invalidargumentexception()
    {
        $this->expectException(InvalidArgumentException::class);
        $dataVehicle = $this->templateData;
        $dataVehicle['type'] =  1000; //unknown type
        (new VehicleDto)->createVehicle( $dataVehicle ); 
        
    }
    
    /**
     * valid creational Vehicle type  unit test should return Vehicletypedtointerface.
     *
     * @return void
     */
    public function test_vehicle_dto_type_should_instanceof_vehicletypedtointerface_n_MotorDto()
    {
        $dataVehicle = $this->templateData;
        $dataVehicle['type'] =  Vehicle::MOTOR_TYPE; 
        $vehicleDtoMotor = (new VehicleDto)->createVehicle($dataVehicle);
        $this->assertTrue(
            $vehicleDtoMotor->getVehicleType() instanceof VehicleTypeDtoInterface && $vehicleDtoMotor->getVehicleType() instanceof MotorDto, 
            "Vehicle type should instance of VehicleTypeDtoInterface and MotorDto"  
        );

    }
    
    /**
     * valid creational Vehicle type  unit test should return Vehicletypedtointerface && CarDto.
     *
     * @return void
     */
    public function test_vehicle_dto_type_should_instanceof_vehicletypedtointerface_n_CarDto()
    {
        $dataVehicle = $this->templateData;
        $dataVehicle['name'] =  'New Honda vario'; 
        $dataVehicle['type'] =  Vehicle::CAR_TYPE; 
        $vehicleDtoCar = (new VehicleDto)->createVehicle( $dataVehicle  );
        $this->assertTrue(
            $vehicleDtoCar->getVehicleType() instanceof VehicleTypeDtoInterface && $vehicleDtoCar->getVehicleType() instanceof CarDto, 
            "Vehicle type should instance of VehicleTypeDtoInterface && CarDto"  
        );
        
    }

    
}
