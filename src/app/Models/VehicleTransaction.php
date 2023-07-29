<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleTransaction extends MongoModel
{
    use HasFactory;

	protected $collection = 'vehicle_transactions';

    const TYPE_IN_STOCK = 1;
    const TYPE_OUT_STOCK = 2;
    const TYPE_HOLD_STOCK = 0;
    
    const STATUS_FAILED = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_CANCEL = 2;

    public static function getTypeTransactions(): array {
        return [
            VehicleTransaction::TYPE_IN_STOCK,
            VehicleTransaction::TYPE_OUT_STOCK,
            VehicleTransaction::TYPE_HOLD_STOCK,
        ];
    }

    protected $fillable = [
        'vehicle_id',
        'qty',
        'type_transaction', //[1 => 'in_stock', 2 =>'out_stock', 0 => 'hold']
        'transaction_detail',//json
        'status',//[ 0=>'Failed', 1=> 'Success', 2=>'Cancel']
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', '_id');
    }
}
