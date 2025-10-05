<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorerouterRequest extends FormRequest
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
        return [
            'mac_address'       => ['required', 'string', 'size:17', Rule::unique('routers', 'mac_address')],
            'name'              => 'required|string|max:255',
            'port'              => 'nullable|integer|min:0|max:65535',
            'ip_address'        => ['required', 'ip', Rule::unique('routers', 'ip_address')],
            'location'          => 'nullable|string|max:255',
            'model'             => 'nullable|string|max:255',
            'manufacturer'      => 'nullable|string|max:255',
            'firmware_version'  => 'nullable|string|max:255',
            'status'            => 'required|in:online,offline',
            'bandwidth'         => 'nullable|integer|min:0',
            'coverage'          => 'nullable|numeric|min:0',
        ];
    }
}
