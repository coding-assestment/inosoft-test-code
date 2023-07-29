<?php

namespace Tests\Unit;

use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use App\Repositories\Dto\VehicleDto;
use App\Repositories\Dto\VehicleTransactionDto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VehicleTransactionDtoTest extends TestCase
{
    protected $vehicleTemplateData = [
        'name' => 'New Honda vario',
        'production_year' => 2023,
        'price' => 1000,
        'qty_in_stock' => 3000,
        'qty_out_stock' => 0,
        'qty_hold' => 0,
        'qty_total' => 0,
        'type' =>  Vehicle::MOTOR_TYPE, //unknown type
    ];

    /**
     * Invalid creational Vehicle Transaction type  outrange 0,1,2 unit test should return ehicletypedtointerface.
     *
     * @return void
     */
    public function test_invalid_transaction_type_should_throw_invalidargumentexception()
    {
        $this->expectException(InvalidArgumentException::class);

        $qtyTransaction = 100;
        $vehicleId = '64c453f6ce82c0c5e2040142';
        $vehicleTransactionType = 1000; // unknown type
        (new VehicleTransactionDto)
            ->createVehicleTransaction($qtyTransaction, $vehicleId, $vehicleTransactionType);
        
    }

    /**
     * Invalid creational Vehicle Transaction with qty <= 0 unit test should return ehicletypedtointerface.
     *
     * @return void
     */
    public function test_invalid_transaction_if_qty_lower_than_1_should_throw_invalidargumentexception()
    {
        $this->expectException(InvalidArgumentException::class);

        $qtyTransaction = 0;
        $vehicleId = '64c453f6ce82c0c5e2040142';
        $vehicleTransactionType = VehicleTransaction::TYPE_OUT_STOCK; // unknown type
        (new VehicleTransactionDto)
            ->createVehicleTransaction($qtyTransaction, $vehicleId, $vehicleTransactionType);
        
    }

    /**
     * Vehicle Sale Transaction with  unit test should calculate and return valid_new_quantity.
     *
     * @return void
     */
    public function test_vehicle_transaction_with_valid_input_sale_data_should_calculate_with_valid_new_quantity_and_return_true()
    {
        $vehicleId = '64c453f6ce82c0c5e2040142';
        $vehicleData = $this->vehicleTemplateData;
        $vehicleData['qty_in_stock'] =   1000;
        $vehicleData['qty_total'] =   5000;
        $vehicleData['_d'] =         $vehicleId ;

        $qtyTransaction = 100;

        $expectedQtyResult = $vehicleData['qty_in_stock'] - $qtyTransaction ;

        $vehiceDto = (new VehicleDto)->createVehicle($vehicleData);
        

        $vehicleTransactionDto = (new VehicleTransactionDto)
            ->createVehicleTransaction($qtyTransaction, $vehicleId, VehicleTransaction::TYPE_OUT_STOCK);
        $vehicleTransactionDto->calculateVehicleData($vehiceDto);
        
        $vehicleDtoResult = $vehicleTransactionDto->getNewVehicleData();

        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_in_stock']??0), $expectedQtyResult);
        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_total']??0), $vehicleData['qty_total'] );
    }
    
    /**
     * Vehicle Sale Transaction with  unit test should calculate and return valid_new_quantity.
     *
     * @return void
     */
    public function test_vehicle_transaction_with_valid_input_sale_data_but_qty_stock_not_enough_should_throw_invalidargumentexception()
    {
        $this->expectException(InvalidArgumentException::class);
        $vehicleId = '64c453f6ce82c0c5e2040142';
        $vehicleData = $this->vehicleTemplateData;
        $vehicleData['qty_in_stock'] =   0;
        $vehicleData['qty_total'] =   5000;
        $vehicleData['_d'] =         $vehicleId ;

        $qtyTransaction = 100;

        $expectedQtyResult = $vehicleData['qty_in_stock'] - $qtyTransaction ;

        $vehiceDto = (new VehicleDto)->createVehicle($vehicleData);
        

        $vehicleTransactionDto = (new VehicleTransactionDto)
            ->createVehicleTransaction($qtyTransaction, $vehicleId, VehicleTransaction::TYPE_OUT_STOCK);
        $vehicleTransactionDto->calculateVehicleData($vehiceDto);
        
        $vehicleDtoResult = $vehicleTransactionDto->getNewVehicleData();

        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_in_stock']??0), $expectedQtyResult);
        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_total']??0), $vehicleData['qty_total'] );
    }
    
    /**
     * Vehicle Add Stock Transaction with  unit test should calculate and return valid_new_quantity.
     *
     * @return void
     */
    public function test_vehicle_transaction_with_valid_input_addStock_data_should_calculate_with_valid_new_quantity_and_return_true()
    {
        $vehicleId = '64c453f6ce82c0c5e2040142';
        $vehicleData = $this->vehicleTemplateData;
        $vehicleData['qty_in_stock'] =   1000;
        $vehicleData['qty_total'] =   1000;
        $vehicleData['_d'] =         $vehicleId ;

        $qtyTransaction = 100;

        $expectedQtyInStock = $vehicleData['qty_in_stock'] + $qtyTransaction ;
        $expectedQtyInStock = $vehicleData['qty_total'] + $qtyTransaction ;

        $vehiceDto = (new VehicleDto)->createVehicle($vehicleData);
        

        $vehicleTransactionDto = (new VehicleTransactionDto)
            ->createVehicleTransaction($qtyTransaction, $vehicleId, VehicleTransaction::TYPE_IN_STOCK);
        $vehicleTransactionDto->calculateVehicleData($vehiceDto);
        
        $vehicleDtoResult = $vehicleTransactionDto->getNewVehicleData();

        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_in_stock']??0), $expectedQtyInStock);
        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_total']??0), $expectedQtyInStock );
    }
    
    /**
     * Vehicle Hold Stock Transaction with  unit test should calculate and return valid_new_quantity.
     *
     * @return void
     */
    public function test_vehicle_transaction_with_valid_input_holdStock_data_should_calculate_with_valid_new_qty_and_return_true()
    {
        $vehicleId = '64c453f6ce82c0c5e2040142';
        $vehicleData = $this->vehicleTemplateData;
        $vehicleData['qty_in_stock'] =   1000;
        $vehicleData['qty_total'] =   1000;
        $vehicleData['qty_hold'] =   10;
        $vehicleData['_d'] =         $vehicleId ;

        $qtyTransaction = 100;

        $expectedQtyInStock = $vehicleData['qty_in_stock'] - $qtyTransaction ;
        $expectedQtyHold = $vehicleData['qty_hold'] + $qtyTransaction ;

        $vehiceDto = (new VehicleDto)->createVehicle($vehicleData);
        

        $vehicleTransactionDto = (new VehicleTransactionDto)
            ->createVehicleTransaction($qtyTransaction, $vehicleId, VehicleTransaction::TYPE_HOLD_STOCK);
        $vehicleTransactionDto->calculateVehicleData($vehiceDto);
        
        $vehicleDtoResult = $vehicleTransactionDto->getNewVehicleData();

        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_in_stock']??0), $expectedQtyInStock);
        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_hold']??0), $expectedQtyHold);
        $this->assertEquals( ($vehicleDtoResult->toArray()['qty_total']??0), $vehicleData['qty_total'] );
    }
}
