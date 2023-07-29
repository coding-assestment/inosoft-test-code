<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends MongoModel
{
    use HasFactory;

	protected $collection = 'vehicles';
	CONST MOTOR_TYPE = 0;
	CONST CAR_TYPE = 1;

	public static $types = [
        Vehicle::MOTOR_TYPE=> 'Motor',
        Vehicle::CAR_TYPE=> 'Car',
    ];

    protected $fillable = [
        'name',
        'production_year',
        'price',
        'qty_in_stock',
        'qty_out_stock',
        'qty_hold',
        'qty_total',
        'type',//[1=>motor, 2=> car]
        'detail',
    ];

    public function vehicleTransactions()
    {
        return $this->hasMany(VehicleTransaction::class, 'vehicle_id');
    }

    public function transaction()
    {
        return $this->hasOne(VehicleTransaction::class, 'vehicle_id');
    }
}
