<?php 
namespace App\Repositories;

use App\Interfaces\VehicleDtoInterface;
use App\Interfaces\VehicleRepositoryInterface;
use App\Interfaces\VehicleTransactionDtoInterface;
use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use InvalidArgumentException;

class VehicleRepository implements VehicleRepositoryInterface
{
    protected Vehicle $vehicleModel ;
    protected VehicleTransaction $vehicleTransactionModel;
    protected VehicleDtoInterface $vehicleDto ;
    protected VehicleTransactionDtoInterface $vehicleTransactionDto ;

    /**
     * Undocumented function
     *
     * @param Vehicle $vehicle
     */
    function __construct(
        Vehicle $vehicle,
        VehicleTransaction $vehicleTransaction,
        VehicleDtoInterface $vehicleDto, 
        VehicleTransactionDtoInterface $vehicleTransactionDto
    ){
        $this->vehicleModel = $vehicle;
        $this->vehicleTransactionModel = $vehicleTransaction;
        $this->vehicleDto = $vehicleDto;
        $this->vehicleTransactionDto = $vehicleTransactionDto;
    }
    
    /**
     * Get all posts.
     *
     * @return Post $vehicle
     */
    public function getAll()
    {
        return $this->vehicleModel ->get();
    }
    
    public function getSales()
    {
        return $this->vehicleTransactionModel->with(['vehicle'])
            ->where('type_transaction', VehicleTransaction::TYPE_OUT_STOCK)
            ->get();
    }

    /**
     * Get Vehicle by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->vehicleModel->with(['vehicleTransactions'=>function ($q) {
            $q->where('type_transaction', VehicleTransaction::TYPE_OUT_STOCK);
        }])
            ->find( $id);
    }

    /**
     * Save Vehicle
     *
     * @param $data
     * @return Vehicle
     */
    // public function save($data)
    public function save( $data )
    {
        $vehicle = new $this->vehicleModel;
        $this->vehicleDto->createVehicle($data);
        foreach ($this->vehicleDto->toArray() as $key => $value) {
            $vehicle->{$key} = $value;
        }

        $vehicle->save();

        return $vehicle->fresh();
    }

    /**
     * Update Vehicle
     *
     * @param $data
     * @return Vehicle
     */
    // public function update($data, $id)
    public function update(array $data, $id)
    {
        
        $this->vehicleDto->createVehicle($data);
        $vehicle = $this->vehicleModel->find($id);
        
        if (is_null($vehicle)) {
            throw new InvalidArgumentException("Invalid Vehicle Id", 1);
        }

        foreach ($this->vehicleDto->toArray() as $key => $value) {
            $vehicle->{$key} = $value;
        }

        $vehicle->update();

        return $vehicle;
    }
    
    /**
     * transaction Vehicle
     *
     * @param $data
     * @return Vehicle
     */
    public function transaction(int $qty, $id, int $typeTransaction)
    {
        
        $vehicle = $this->vehicleModel->find($id);
        if (is_null($vehicle)) {
            throw new InvalidArgumentException("Invalid Vehicle Id", 1);
        }
        $this->vehicleDto->createVehicle($vehicle->toArray());
        
        $this->vehicleTransactionDto->createVehicleTransaction($qty, $id, $typeTransaction);
        $this->vehicleTransactionDto->calculateVehicleData($this->vehicleDto);
        $this->vehicleDto = $this->vehicleTransactionDto->getNewVehicleData();
        
        foreach ($this->vehicleDto->toArray() as $key => $value) {
            $vehicle->{$key} = $value;
        }

        $vehicle->update();
        return $vehicle->vehicleTransactions()->create($this->vehicleTransactionDto->toArray());

        return $vehicle;
    }

    /**
     * Update Vehicle
     *
     * @param $data
     * @return Vehicle
     */
    public function delete($id)
    {
        
        $vehicle = $this->vehicleModel->find($id);
        if (is_null($vehicle)) {
            throw new InvalidArgumentException("Invalid Vehicle Id", 1);
        }
        $vehicle->delete();

        return $vehicle;
    }
}
