<?php

namespace App\Repositories\Dto;

use App\Interfaces\VehicleDtoInterface;
use App\Interfaces\VehicleTransactionDtoInterface;
use App\Interfaces\VehicleTypeDtoInterface;
use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use InvalidArgumentException;

class VehicleTransactionDto implements VehicleTransactionDtoInterface
{
    protected $data = [];
    protected int $qty;
    protected int $typeTransaction;
    protected VehicleDtoInterface $vehicleData;
    protected VehicleDtoInterface $NewVehicleData;
    protected array $vehicleTypeTransasctionHandler = [
        VehicleTransaction::TYPE_IN_STOCK => 'inStock',
        VehicleTransaction::TYPE_OUT_STOCK => 'outStock',
        VehicleTransaction::TYPE_HOLD_STOCK => 'holdStock',
    ];

    public function createVehicleTransaction(int $qty, $vehicleId, int $typeTransaction):VehicleTransactionDtoInterface{
        
        if (!in_array($typeTransaction, VehicleTransaction::getTypeTransactions())) {
            throw new InvalidArgumentException("Invalid typeTransaction", 1);
        }

        if ( $qty <=0 ) {
            throw new InvalidArgumentException("Invalid Qty Transaction", 1);
        }

        $this->typeTransaction = $typeTransaction;
        $this->qty = $qty;

        $this->data = [
            'vehicle_id' => $vehicleId,
            'qty' => $qty,
            'type_transaction' => $typeTransaction, 
            'transaction_detail' => 'xx', 
            'status'=> VehicleTransaction::STATUS_SUCCESS,//[ 0=>'Failed', 1=> 'Success', 2=>'Cancel']
        ];
        
        return $this;
    }

    public function calculateVehicleData(VehicleDtoInterface $vehicleDto):VehicleTransactionDtoInterface{
        
        $this->vehicleData = $vehicleDto;
        $this->NewVehicleData = clone $vehicleDto;
        
        $handler = $this->vehicleTypeTransasctionHandler[$this->typeTransaction];
        $this->{$handler}();

        return $this;
    }

    private function inStock() : void {
        $data = $this->NewVehicleData->toArray();
        $data['qty_in_stock'] = $data['qty_in_stock']+$this->qty;
        $data['qty_total'] = $data['qty_total']+$this->qty;
        $this->NewVehicleData->createVehicle($data);
    }
    
    private function outStock() : void {
        $data = $this->NewVehicleData->toArray();
        if ( $data['qty_in_stock'] <=0 ) {
            throw new InvalidArgumentException("Invalid Qty Stock Transaction", 1);
        }

        if ( $data['qty_in_stock'] < $this->qty ) {
            throw new InvalidArgumentException("Invalid Transaction, Qty Stock should greater than or equal Qty Transaction ", 1);
        }

        $data['qty_in_stock'] = $data['qty_in_stock']-$this->qty;
        $data['qty_out_stock'] = $data['qty_out_stock']+$this->qty;
        $this->NewVehicleData->createVehicle($data);
    }
    
    private function holdStock() : void {
        $data = $this->NewVehicleData->toArray();
        $data['qty_in_stock'] = $data['qty_in_stock']-$this->qty;
        $data['qty_hold'] = $data['qty_hold']+$this->qty;
        $this->NewVehicleData->createVehicle($data);
    }

    public function getOldVehicleData():VehicleDtoInterface{
        return $this->NewVehicleData;
    }

    public function getNewVehicleData():VehicleDtoInterface{
        return $this->NewVehicleData;
    }

    public function toArray():array{
        return $this->data;
    }
    
    public function toJson():string{
        return json_encode($this->data);
    }
}