<?php 
namespace App\Services;

use App\Interfaces\VehicleDtoInterface;
use App\Interfaces\VehicleRepositoryInterface;
use App\Interfaces\VehicleTransactionDtoInterface;
use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use App\Repositories\VehicleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\Rule;

class VehicleService
{
    protected VehicleRepositoryInterface $vehicleRepository ;
    protected VehicleDtoInterface $vehicleDto ;
    protected VehicleTransactionDtoInterface $vehicleTransactionDto ;
    
    /**
     * Undocumented function
     *
     * @param Vehicle $vehicle
     */
    function __construct(
        VehicleRepositoryInterface $vehicleRepository, 
        VehicleDtoInterface $vehicleDto, 
        VehicleTransactionDtoInterface $vehicleTransactionDto
    ){

        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDto = $vehicleDto;
        $this->vehicleTransactionDto = $vehicleTransactionDto;

    }   
    
    /**
     * Delete post by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        // DB::beginTransaction();

        try {
            $vehicle = $this->vehicleRepository->delete($id);

        } catch (Exception $e) {
            // DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete vehicle data');
        }

        // DB::commit();

        return $vehicle;

    }

    /**
     * Get all post.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->vehicleRepository->getAll();
    }
    
    /**
     * Get all post.
     *
     * @return String
     */
    public function getSales()
    {
        return $this->vehicleRepository->getSales();
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        $vehicleData = $this->vehicleRepository->getById($id);
        if (is_null($vehicleData)) {
            throw new InvalidArgumentException('Unable to find vehicle data');
        }
        return $vehicleData;
    }

    /**
     * Update post data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updatePost($data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'type' => ['required', Rule::in([Vehicle::MOTOR_TYPE, Vehicle::CAR_TYPE])],
            'price' => 'required',
            'machine' => 'required',

            'supenstion_type' => 'required_if:type,'.Vehicle::MOTOR_TYPE,
            'transmission_type' => 'required_if:type,'.Vehicle::MOTOR_TYPE,

            'passanger_capacity' => 'required_if:type,'.Vehicle::CAR_TYPE,
            'car_type' => 'required_if:type,'.Vehicle::CAR_TYPE,
        ]);

        if ($validator->fails()) {
            // throw new InvalidArgumentException($validator->errors()->first());
            throw new InvalidArgumentException($validator->errors());
        }

        // DB::beginTransaction();

        try {
            
            // $vehicle = $this->vehicleRepository->update($data, $id);
            $vehicle = $this->vehicleRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update vehicle data');
        }

        // DB::commit();

        return $vehicle;

    }

    /**
     * Validate post data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($data)
    {
        
        $validator = Validator::make($data, [
            'name' => 'required',
            'price' => 'required',
            'type' => ['required', Rule::in([Vehicle::MOTOR_TYPE, Vehicle::CAR_TYPE])],
            'machine' => 'required',

            'supenstion_type' => 'required_if:type,'.Vehicle::MOTOR_TYPE,
            'transmission_type' => 'required_if:type,'.Vehicle::MOTOR_TYPE,

            'passanger_capacity' => 'required_if:type,'.Vehicle::CAR_TYPE,
            'car_type' => 'required_if:type,'.Vehicle::CAR_TYPE,

        ]);
        
        if ($validator->fails()) {
            // throw new InvalidArgumentException($validator->errors()->first());
            throw new InvalidArgumentException($validator->errors());
        }
        
        // dd($this->vehicleDto->createVehicle($data)->toArray());
        $result = $this->vehicleRepository->save($data);

        return $result;
    }
    
    /**
     * Validate savelVehicle data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function vehicleTransaction($data, $id, int $typeTransaction)
    {
        
        $validator = Validator::make([
            'qty' => $data['qty']??0,
            'type_transaction' => $typeTransaction,
            '_id' => $id,
        ], [
            'qty' => 'required',
            'type_transaction' => ['required', Rule::in([
                VehicleTransaction::TYPE_IN_STOCK, 
                VehicleTransaction::TYPE_OUT_STOCK,
                VehicleTransaction::TYPE_HOLD_STOCK,
                ])],
            '_id' => 'required',
        ]);

        if ($validator->fails()) {
            // throw new InvalidArgumentException($validator->errors()->first());
            throw new InvalidArgumentException($validator->errors());
        }

        // DB::beginTransaction();

        try {

            $vehicle = $this->vehicleRepository->transaction($data['qty']??0, $id, $typeTransaction);

        } catch (Exception $e) {
            // DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update transaction data, Error: '.$e->getMessage());
        }

        // DB::commit();

        return $vehicle;
    }
}
