<?php

namespace App\Interfaces;

use App\Models\Vehicle;

abstract class VehicleDtoAbstract
{
    protected array $data = [];
    protected array $detail = [];
    protected bool $isValid = false;

    abstract public function buildDetail(array $data):VehicleDtoAbstract;
    /* abstract public function getDetail():array;
    abstract public function hasValidDetail() : bool; */

    public function createVehicle(array $data = []) {

        $this->data = array_merge([
            'name' => $data['name']??'',
            'production_year' => $data['production_year']??date("Y"),
            'price' => $data['price']??0,
            'qty_in_stock' => $data['qty_in_stock']??0,
            'qty_out_stock' => $data['qty_out_stock']??0,
            'qty_hold' => $data['qty_hold']??0,
            'qty_total' => $data['qty_total']??0,
            'type' =>  $data['type']??1,
        ], $data);

        return $this;
    }

    function setDetail(VehicleDtoAbstract $detail) {
        if ($detail->hasValidDetail()) {
            $this->data['detail'] = $detail->getDetail();
        }

        return $this;
    }

    public function getDetail():array{
        return $this->detail ;
    }

    public function hasValidDetail() : bool{
        return $this->isValid;
    }



    public function toArray():array {
        return $this->data;
    }

    public function toJson():string {
        return json_encode($this->data);
    }
}