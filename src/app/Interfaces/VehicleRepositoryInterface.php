<?php

namespace App\Interfaces;

interface VehicleRepositoryInterface 
{
    public function getAll();
    public function getSales();
    public function getById($id);
    public function save(array $data);
    public function update(array $data, $id);
    public function transaction(int $qty, $id, int $typeTransaction);
    public function delete($id);
}