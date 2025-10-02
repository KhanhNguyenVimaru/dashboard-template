<?php

namespace App\Http\Controllers;

use App\Models\IotDevice;
use App\Http\Requests\StoreIotDeviceRequest;
use App\Http\Requests\UpdateIotDeviceRequest;
use Illuminate\Http\Request;

class IotDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 5);

        // nếu như api gửi có search
        if ($request->has('search')) {
            $keyword = $request->query('search');
            return IotDevice::search($keyword)->paginate($perPage);
        }

        if ($request->has('serial_number')) {
            return IotDevice::with('devicesPosition')->where('serial_number', $request->query('serial_number'))->firstOrFail();
        }

        return IotDevice::paginate($perPage);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIotDeviceRequest $request)
    {
        $iotDevice = IotDevice::create($request->validated()); // validate là dữ liệu đã được xác thực
        return response()->json($iotDevice, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(IotDevice $iotDevice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IotDevice $iotDevice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIotDeviceRequest $request, IotDevice $iotDevice)
    {
        $iotDevice->update($request->validated());

        return response()->json($iotDevice->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IotDevice $iotDevice)
    {
        $iotDevice->delete();

        return response()->noContent(); // 204
    }
}
