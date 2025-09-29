<?php

namespace App\Http\Requests;

use App\Models\IotDevice;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIotDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $iotDevice = $this->route('iotDevice');
        $deviceId = $iotDevice instanceof IotDevice ? $iotDevice->id : $iotDevice;

        return [
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|in:sensor,plc,cnc,rfid,robot,hmi,other',
            'serial_number' => 'required|string|max:100|unique:iot_devices,serial_number,' . $deviceId,
            'ip_address' => 'nullable|ip',
            'port' => 'nullable|integer|min:0|max:65535',
            'mac_address' => 'nullable|string|size:17',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'config' => 'nullable|json',
            'is_active' => 'boolean',
            'coverage' => 'nullable|numeric|min:0',
            'protocol' => 'required|in:mqtt,http,modbus,coap,tcp,udp,other',
            'firmware_version' => 'nullable|string|max:100',
        ];
    }
}
