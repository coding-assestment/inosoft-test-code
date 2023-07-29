<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use App\Services\VehicleService;
use Exception;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    
    /**
     * @var postService
     */
    protected $vehicleService;

    /**
     * PostController Constructor
     *
     * @param PostService $vehicleService
     *
     */
    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales()
    {
        $result = ['status' => 200];

        try {
            
            $result['data'] = $this->vehicleService->getSales();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'price',
            'type',
            'machine',
            'supenstion_type',
            'transmission_type',
            'passanger_capacity',
            'car_type',
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->savePostData($data);
        } catch (Exception $e) {
            
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = ['status' => 200];
        
        try {
            $result['data'] = $this->vehicleService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $post)
    {
        //
    }

    /**
     * Update post.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'name',
            'type',
            'price',
            'machine',
            'supenstion_type',
            'transmission_type',
            'passanger_capacity',
            'car_type',
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->deleteById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    /**
     * addStock post.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function addStock(Request $request, $id)
    {
        $data = $request->only([
            'qty',
            'transaction_detail'
        ]);
        
        // $data['type_transaction'] = VehicleTransaction::TYPE_IN_STOCK;
        $data['id'] = $id;

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->vehicleTransaction($data, $id, VehicleTransaction::TYPE_IN_STOCK);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);

    }

    /**
     * sale post.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function sale(Request $request, $id)
    {
        $data = $request->only([
            'qty',
            'transaction_detail',
        ]);
        
        // $data['type_transaction'] = VehicleTransaction::TYPE_OUT_STOCK;
        $data['id'] = $id;

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->vehicleTransaction($data, $id, VehicleTransaction::TYPE_OUT_STOCK);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);

    }

    /**
     * sale post.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function hold(Request $request, $id)
    {
        $data = $request->only([
            'qty',
            'transaction_detail',
        ]);
        
        // $data['type_transaction'] = VehicleTransaction::TYPE_HOLD_STOCK;
        $data['id'] = $id;

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->vehicleTransaction($data, $id, VehicleTransaction::TYPE_HOLD_STOCK);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => json_decode($e->getMessage())??$e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);

    }

}
